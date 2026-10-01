<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WelcomeContent;
use Illuminate\Http\Request;

class WelcomeContentController extends Controller
{
    public function edit()
    {
        $content = WelcomeContent::first() ?? WelcomeContent::create([
            'title' => 'Welcome to Our CBO',
            'subtitle' => 'Empowering our community.',
            'body_content' => 'Default welcome text...'
        ]);

        return view('admin.welcome-edit', compact('content'));
    }

    public function update(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string',
        'body_content' => 'required|string',
        'mission' => 'nullable|string',
        'vision' => 'nullable|string',
    ]);

    $content = WelcomeContent::first();
    $content->update($request->only('title', 'subtitle', 'body_content', 'mission', 'vision'));

    return redirect()->route('admin.welcome.edit')->with('success', 'Welcome page updated successfully!');
}
}
