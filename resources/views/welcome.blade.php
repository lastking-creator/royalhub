@php
    $content = \App\Models\WelcomeContent::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JVT CBO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navigation -->
    <nav class="bg-emerald-700 text-white px-6 py-4 flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">JASINI VIJANA THABITI CBO</h1>
        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="px-4 py-2 bg-emerald-900 rounded-md hover:bg-emerald-800 text-sm font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold hover:underline mr-4">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-emerald-900 rounded-md hover:bg-emerald-800 text-sm font-semibold">Register</a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Dynamic Hero Section -->
    <section class="bg-emerald-600 text-white text-center py-16 px-4">
        <h2 class="text-4xl font-extrabold mb-4">{{ $content->title ?? 'Empowering Our Local Community' }}</h2>
        <p class="text-lg max-w-2xl mx-auto mb-6">{{ $content->subtitle ?? 'Joining hands to create sustainable solutions, support families, and build a brighter future for everyone.' }}</p>
        
        <div class="space-x-3">
            <a href="{{ route('register') }}" class="inline-block bg-gray-900 text-white font-bold px-6 py-3 rounded-lg shadow hover:bg-gray-800 transition">Get Started / Join Us</a>
            <a href="{{ route('login') }}" class="inline-block bg-white text-emerald-800 font-bold px-6 py-3 rounded-lg shadow hover:bg-gray-100 transition">Member Login</a>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="max-w-4xl mx-auto mt-12 px-4">
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-emerald-50 border-l-4 border-emerald-700 p-6 rounded-r-lg shadow-sm">
                <h3 class="text-xl font-bold text-emerald-900 mb-2">Our Mission</h3>
                <p class="text-gray-700 text-sm leading-relaxed">
                    {{ $content->mission ?? 'A thriving community where the environment is protected, young people are empowered and self-reliant, coastal resources support sustainable livelihoods and every person lives free from gender-based violence.' }}
                </p>
            </div>

            <div class="bg-emerald-50 border-l-4 border-emerald-700 p-6 rounded-r-lg shadow-sm">
                <h3 class="text-xl font-bold text-emerald-900 mb-2">Our Vision</h3>
                <p class="text-gray-700 text-sm leading-relaxed">
                    {{ $content->vision ?? 'To organize and empower the youth and the community to take practical action on environmental conservation, economic empowerment, sustainable use of coastal resources, and the prevention of gender-based violence, through community-led projects, training, and partnerships.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Dynamic CBO Body Content -->
    <main class="max-w-4xl mx-auto my-12 px-4 space-y-8">
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-2xl font-bold mb-2 text-emerald-800">About Our Organization</h3>
            <div class="leading-relaxed text-gray-600 whitespace-pre-line">
                {{ $content->body_content ?? 'We are a non-profit Community-Based Organization dedicated to improving livelihoods through education, healthcare initiatives, and youth empowerment projects across our regions.' }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-2xl font-bold mb-4 text-emerald-800">Our Core Pillars</h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-4 bg-emerald-50 rounded-md">
                    <h4 class="font-semibold text-emerald-900 text-lg">Youth Empowerment</h4>
                    <p class="text-sm text-gray-600 mt-1">Providing vocational training, digital literacy, and leadership mentorship.</p>
                </div>
                <div class="p-4 bg-emerald-50 rounded-md">
                    <h4 class="font-semibold text-emerald-900 text-lg">Blue Economy</h4>
                    <p class="text-sm text-gray-600 mt-1">Organizing free medical camps, sanitation drives, and hygiene awareness.</p>
                </div>
                <div class="p-4 bg-emerald-50 rounded-md">
                    <h4 class="font-semibold text-emerald-900 text-lg">Environmental Action</h4>
                    <p class="text-sm text-gray-600 mt-1">Leading indigenous tree planting and local waste management projects.</p>
                </div>
                <div class="p-4 bg-emerald-50 rounded-md">
                    <h4 class="font-semibold text-emerald-900 text-lg">GBV Prevention</h4>
                    <p class="text-sm text-gray-600 mt-1">Leading indigenous tree planting and local waste management projects.</p>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 text-center py-6">
        <p>&copy; {{ date('Y') }} JASINI VIJANA THABITI CBO. All rights reserved.</p>
    </footer>

</body>
</html>