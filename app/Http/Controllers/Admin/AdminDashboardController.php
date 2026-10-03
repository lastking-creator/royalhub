<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\Project;
use App\Models\CommunityBeneficiary;
use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Grant;
use App\Models\GrantDeliverable;
use App\Models\GrantDocument;
use App\Models\ComplianceDocument;
use App\Models\CommunicationLog;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingMembers = User::where('status', 'pending')->latest()->get();
        $approvedMembersCount = User::where('status', 'approved')->count();
        $totalMembersCount = User::where('status', 'approved')->count();

        // Calculate total financial metrics
        $totalDonations = Donation::sum('amount');
        $totalExpenses = Expense::sum('amount');

        // Calculate remaining balance
        $remainingBalance = $totalDonations - $totalExpenses;

        return view('admin.dashboard', compact(
            'pendingMembers', 
            'approvedMembersCount', 
            'totalMembersCount',
            'totalDonations',
            'totalExpenses',
            'remainingBalance'
        ));
    }

    public function approveMember(User $user)
    {
        if (!$user->registration_number) {
            $lastNumber = User::whereNotNull('registration_number')
                ->where('registration_number', 'like', 'MCHV/%')
                ->get()
                ->map(function ($u) {
                    return (int) str_replace('MCHV/', '', $u->registration_number);
                })
                ->max();

            $nextId = ($lastNumber ?? 0) + 1;
            $regNumber = 'MCHV/' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        } else {
            $regNumber = $user->registration_number;
        }

        $user->update([
            'status' => 'approved',
            'registration_number' => $regNumber,
        ]);

        return redirect()->back()->with('success', "Member {$user->name} approved with Reg No: {$regNumber}");
    }

    public function declineMember(User $user)
    {
        $user->update(['status' => 'declined']);

        return redirect()->back()->with('success', "Member {$user->name} application has been declined.");
    }

    // User / Member Management
    public function members()
    {
        $members = User::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.members', compact('members'));
    }

    public function importMembers(Request $request)
    {
        return back()->with('success', 'Members imported successfully!');
    }

    /**
     * Display printable member registration form.
     */
    public function printRegistrationForm($id)
    {
        $member = User::findOrFail($id);

        $passportPhotoBase64 = null;
        if ($member->passport_photo && Storage::disk('public')->exists($member->passport_photo)) {
            $fileContents = Storage::disk('public')->get($member->passport_photo);
            $mimeType = Storage::disk('public')->mimeType($member->passport_photo);
            $passportPhotoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
        }

        return view('admin.members.pdf-registration', compact('member', 'passportPhotoBase64'));
    }

    // Programs & Projects
    public function programs()
    {
        $projects = Project::with(['manager', 'programs', 'donations', 'expenses'])->latest()->get();
        $programs = Program::with('project')->latest()->get();
        $cboMembers = User::all();

        return view('admin.programs', compact('projects', 'programs', 'cboMembers'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'target_community' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'budget' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        Project::create([
            'title' => $request->title,
            'target_community' => $request->target_community,
            'manager_id' => $request->manager_id,
            'budget' => $request->budget ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.programs')->with('success', 'Project created with timeframe successfully!');
    }

    public function storeProgram(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Program::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.programs')->with('success', 'Program attached to Project successfully!');
    }

    // Events & Attendance
    public function events()
    {
        return view('admin.events');
    }

    // Donations & Finance
    public function finance()
    {
        $donors = Donor::with('donations')->get();
        $members = User::where('status', 'approved')->orderBy('name')->get();
        
        $donorDonations = Donation::with(['donor', 'project'])->whereNotNull('donor_id')->latest()->get();
        $memberContributions = Donation::with(['user', 'project'])->whereNotNull('user_id')->latest()->get();
        
        $expenses = Expense::with('project')->latest()->get();
        
        // Eager load both donations and expenses for project summary reflections
        $projects = Project::with(['expenses', 'donations'])->get();

        return view('admin.finance', compact('donors', 'members', 'donorDonations', 'memberContributions', 'expenses', 'projects'));
    }

    // Store New External Donor Profile
    public function storeDonor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Donor::create($validated);

        return redirect()->back()->with('success', 'Donor profile created successfully.');
    }

    // Store External Donor Donation
    public function storeDonorDonation(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'type' => 'required|in:cash,in-kind',
            'amount' => 'nullable|numeric',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'nullable|string',
            'donated_at' => 'required|date',
        ]);

        Donation::create($validated);

        return redirect()->back()->with('success', 'Donor donation recorded successfully.');
    }

    // Store Registered Member Contribution
    public function storeMemberContribution(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'purpose' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'donated_at' => 'required|date',
        ]);

        $validated['type'] = 'cash';

        Donation::create($validated);

        return redirect()->back()->with('success', 'Member contribution recorded successfully.');
    }

    // Store New Financial Expense
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expensed_at' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create($validated);

        return redirect()->back()->with('success', 'Expense recorded successfully.');
    }

    // Export Financial Data (Donations/Contributions or Expenses) as CSV
    public function exportFinanceData($type)
    {
        $fileName = $type . '-report-' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'donations') {
                fputcsv($file, ['ID', 'Contributor / Donor', 'Category Type', 'Amount (KSh)', 'Payment Method', 'Reference Code', 'Purpose / Description', 'Project', 'Date']);
                
                $donations = Donation::with(['donor', 'user', 'project'])->latest()->get();
                
                foreach ($donations as $row) {
                    $contributor = $row->user ? $row->user->name : ($row->donor->name ?? 'Anonymous');
                    $categoryType = $row->user_id ? 'Member Contribution' : 'External Donor Grant';
                    
                    fputcsv($file, [
                        $row->id, 
                        $contributor, 
                        $categoryType,
                        $row->amount, 
                        $row->payment_method ?? 'N/A',
                        $row->reference_number ?? 'N/A',
                        $row->purpose ?? $row->description ?? 'N/A', 
                        $row->project->title ?? 'General Fund', 
                        Carbon::parse($row->donated_at)->format('Y-m-d')
                    ]);
                }
            } else {
                fputcsv($file, ['ID', 'Project', 'Expense Title', 'Category', 'Amount (KSh)', 'Date']);
                
                $expenses = Expense::with('project')->latest()->get();
                
                foreach ($expenses as $row) {
                    fputcsv($file, [
                        $row->id, 
                        $row->project->title ?? 'General', 
                        $row->title, 
                        $row->category ?? 'N/A', 
                        $row->amount, 
                        Carbon::parse($row->expensed_at)->format('Y-m-d')
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Grants & Reporting
    public function grants()
    {
        $grants = Grant::with(['deliverables', 'documents'])->latest()->get();
        return view('admin.grants', compact('grants'));
    }

    public function storeGrant(Request $request)
    {
        $request->validate([
            'funder_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'status' => 'required|in:draft,submitted,awarded,rejected',
        ]);

        Grant::create($request->all());
        return back()->with('success', 'Grant added successfully.');
    }

    public function storeGrantDeliverable(Request $request)
    {
        $request->validate([
            'grant_id' => 'required|exists:grants,id',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        GrantDeliverable::create($request->all());
        return back()->with('success', 'Deliverable tracked.');
    }

    public function storeGrantDocument(Request $request)
    {
        $request->validate([
            'grant_id' => 'required|exists:grants,id',
            'title' => 'required|string|max:255',
            'category' => 'required|in:proposal,compliance,financial_report,narrative_report,other',
            'document' => 'required|file|mimes:pdf,doc,docx,png,jpg|max:5120',
        ]);

        $path = $request->file('document')->store('grant_documents', 'public');

        GrantDocument::create([
            'grant_id' => $request->grant_id,
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function exportGrantReport(Grant $grant)
    {
        $fileName = 'grant-report-' . Str::slug($grant->title) . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($grant) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['GRANT SUMMARY REPORT']);
            fputcsv($file, ['Funder', $grant->funder_name]);
            fputcsv($file, ['Grant Title', $grant->title]);
            fputcsv($file, ['Amount (KSh)', $grant->amount]);
            fputcsv($file, ['Status', ucfirst($grant->status)]);
            fputcsv($file, []);

            fputcsv($file, ['DELIVERABLES']);
            fputcsv($file, ['Title', 'Due Date', 'Status', 'Notes']);
            foreach ($grant->deliverables as $deliv) {
                fputcsv($file, [$deliv->title, $deliv->due_date->format('Y-m-d'), ucfirst($deliv->status), $deliv->notes ?? '']);
            }
            fputcsv($file, []);

            fputcsv($file, ['ATTACHED DOCUMENTS & COMPLIANCE']);
            fputcsv($file, ['Title', 'Category', 'Date Uploaded']);
            foreach ($grant->documents as $doc) {
                fputcsv($file, [$doc->title, ucfirst(str_replace('_', ' ', $doc->category)), $doc->created_at->format('Y-m-d')]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Communications
    public function communications()
    {
        $logs = CommunicationLog::with('sender')->latest()->paginate(15);
        $totalMembers = User::count();
        $approvedMembers = User::where('status', 'approved')->count();

        return view('admin.communications', compact('logs', 'totalMembers', 'approvedMembers'));
    }

    public function sendCommunication(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:email,sms,announcement',
            'recipient_group' => 'required|string',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        CommunicationLog::create([
            'type' => $validated['type'],
            'recipient_group' => $validated['recipient_group'],
            'subject' => $validated['subject'] ?? 'N/A',
            'message' => $validated['message'],
            'status' => 'sent',
            'sender_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', ucfirst($validated['type']) . ' broadcast sent and logged successfully.');
    }

    // Documents & Compliance
    public function documents()
    {
        $documents = ComplianceDocument::with('uploader')->latest()->get();

        $expiringSoon = ComplianceDocument::whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now()->addDays(30))
            ->where('expires_at', '>=', Carbon::now())
            ->get();

        $expired = ComplianceDocument::whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        return view('admin.documents', compact('documents', 'expiringSoon', 'expired'));
    }

    public function storeComplianceDocument(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:registration_cert,tax_status,policy,license,other',
            'document' => 'required|file|mimes:pdf,doc,docx,png,jpg|max:10240',
            'version' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $path = $request->file('document')->store('compliance_documents', 'public');

        ComplianceDocument::create([
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $path,
            'version' => $request->version,
            'expires_at' => $request->expires_at,
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Compliance document uploaded successfully.');
    }

    // Reports & Analytics
    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfYear()->format('Y-m-d'));

        // Financial Metrics
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $netBalance = $totalDonations - $totalExpenses;

        // Member Metrics
        $approvedMembers = User::where('status', 'approved')->whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingMembers = User::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count();

        // Query monthly totals
        $rawDonations = Donation::selectRaw("strftime('%m', created_at) as month, SUM(amount) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')->pluck('total', 'month')->toArray();

        $rawExpenses = Expense::selectRaw("strftime('%m', created_at) as month, SUM(amount) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')->pluck('total', 'month')->toArray();

        // Format into 12-month zero-padded arrays (01-12)
        $monthlyDonations = [];
        $monthlyExpenses = [];

        for ($m = 1; $m <= 12; $m++) {
            $key = sprintf('%02d', $m);
            $monthlyDonations[] = (float) ($rawDonations[$key] ?? 0);
            $monthlyExpenses[] = (float) ($rawExpenses[$key] ?? 0);
        }

        return view('admin.reports', compact(
            'totalDonations', 'totalExpenses', 'netBalance',
            'approvedMembers', 'pendingMembers', 'startDate', 'endDate',
            'monthlyDonations', 'monthlyExpenses'
        ));
    }

    // Account Management
    public function accounts()
    {
        $users = User::all();
        return view('admin.accounts', compact('users'));
    }

    public function editAccount(User $user)
    {
        return view('admin.accounts-edit', compact('user'));
    }

    public function updateAccount(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string',
            'password' => 'nullable|min:8',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.accounts')->with('success', 'Account updated successfully!');
    }

    public function deleteAccount(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account!');
        }

        $user->delete();

        return redirect()->route('admin.accounts')->with('success', 'Account deleted successfully!');
    }
}