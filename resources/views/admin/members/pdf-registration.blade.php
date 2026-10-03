<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - {{ $member->name }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            color: #1f2937; 
            line-height: 1.5; 
            position: relative;
        }

        /* Watermark Styles */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08; /* Adjust opacity (0.05 - 0.15 works best) */
            width: 70%;
            max-width: 500px;
            z-index: -1000;
            pointer-events: none;
        }

        .header { 
            text-align: center; 
            border-bottom: 2px solid #4f46e5; 
            padding-bottom: 12px; 
            margin-bottom: 24px; 
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 14px;
            color: #374151;
            margin-top: 20px;
            margin-bottom: 12px;
            border-left: 4px solid #4f46e5;
        }
        .grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .row {
            display: table-row;
        }
        .cell-label {
            display: table-cell;
            width: 35%;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 13px;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }
        .cell-value {
            display: table-cell;
            width: 65%;
            padding: 6px 8px;
            font-size: 13px;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }
        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                margin: 0; 
            }
            .watermark {
                opacity: 0.08 !important; /* Preserves watermark visibility in print/PDF */
            }
        }
    </style>
</head>
<body>

    <!-- Watermark Background Image -->
    <div class="watermark">
        <img src="{{ asset('images/cbo-logo.png') }}" style="width: 100%; height: auto;" alt="Watermark">
    </div>

    <!-- Print Action Header -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background: #4f46e5; color: #ffffff; padding: 8px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            Print / Save as PDF
        </button>
    </div>

    <!-- Document Header with Logo -->
    <div class="header" style="position: relative;">
        @if($passportPhotoBase64)
            <div style="position: absolute; top: 0; right: 0; width: 90px; height: 110px; border: 1px solid #d1d5db; padding: 2px;">
                <img src="{{ $passportPhotoBase64 }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Member Photo">
            </div>
        @endif
        <div style="margin-bottom: 10px;">
            <img src="{{ asset('images/cbo-logo.png') }}" alt="CBO Logo" style="max-height: 80px; width: auto; display: inline-block;">
        </div>
        <h2 style="margin: 0; color: #4f46e5; text-transform: uppercase;">COMMUNITY BASED ORGANIZATION</h2>
        <h4 style="margin: 4px 0 0 0; font-weight: normal; color: #6b7280;">Official Member Registration Form</h4>
    </div>

    <!-- Official System Details -->
    <div class="section-title">Official Details</div>
    <div class="grid">
        <div class="row">
            <div class="cell-label">Registration Number:</div>
            <div class="cell-value"><strong>{{ $member->registration_number ?? 'N/A' }}</strong></div>
        </div>
        <div class="row">
            <div class="cell-label">Application Status:</div>
            <div class="cell-value">{{ ucfirst($member->status ?? 'Approved') }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Registration Date:</div>
            <div class="cell-value">{{ $member->created_at ? $member->created_at->format('F d, Y') : 'N/A' }}</div>
        </div>
    </div>

    <!-- 1. Personal Information -->
    <div class="section-title">1. Personal Information</div>
    <div class="grid">
        <div class="row">
            <div class="cell-label">Full Name:</div>
            <div class="cell-value">{{ $member->name }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Date of Birth:</div>
            <div class="cell-value">
                {{ $member->dob ?? $member->date_of_birth ?? $member->birthdate ?? 'N/A' }}
            </div>
        </div>
        <div class="row">
            <div class="cell-label">National ID Number:</div>
            <div class="cell-value">{{ $member->national_id ?? $member->id_number ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Physical Address / Village:</div>
            <div class="cell-value">
                {{ $member->address ?? $member->village ?? $member->physical_address ?? $member->location ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- 2. Contact Details -->
    <div class="section-title">2. Contact Details</div>
    <div class="grid">
        <div class="row">
            <div class="cell-label">Primary Phone Number:</div>
            <div class="cell-value">
                {{ $member->phone ?? $member->phone_number ?? $member->primary_phone ?? $member->mobile ?? 'N/A' }}
            </div>
        </div>
        <div class="row">
            <div class="cell-label">WhatsApp Number:</div>
            <div class="cell-value">{{ $member->whatsapp_number ?? $member->whatsapp ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Email Address:</div>
            <div class="cell-value">{{ $member->email }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Next of Kin Name:</div>
            <div class="cell-value">{{ $member->next_of_kin_name ?? $member->next_of_kin ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Next of Kin Relationship:</div>
            <div class="cell-value">{{ $member->next_of_kin_relationship ?? $member->relationship ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Next of Kin Phone Number:</div>
            <div class="cell-value">{{ $member->next_of_kin_phone ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- 3. Skills & Areas of Interest -->
    <div class="section-title">3. Skills & Areas of Interest</div>
    <div class="grid">
        <div class="row">
            <div class="cell-label">Occupation:</div>
            <div class="cell-value">{{ $member->occupation ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="cell-label">Talents & Special Skills:</div>
            <div class="cell-value">
                {{ $member->skills ?? $member->talents ?? $member->special_skills ?? $member->skills_and_talents ?? 'N/A' }}
            </div>
        </div>
    </div>

</body>
</html>