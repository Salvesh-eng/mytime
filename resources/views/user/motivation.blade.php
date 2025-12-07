@extends('layouts.app')

@section('page-title', 'Motivation & Entertainment')

@section('content')
<style>
    :root {
        --soft-pink: #FFB3D9;
        --soft-blue: #B3D9FF;
        --soft-green: #B3FFB3;
        --soft-orange: #FFD9B3;
        --soft-purple: #D9B3FF;
        --soft-peach: #FFCCB3;
        --soft-mint: #B3FFD9;
        --soft-lavender: #E6D9FF;
        --light-green: #C8E6C9;
        --light-pink: #F8BBD0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f0fdf4 0%, #f0f9ff 50%, #fdf2f8 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(255, 179, 217, 0.15) 100%);
        border-radius: 16px;
        padding: 24px;
        border: 2px solid rgba(179, 255, 179, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);
        margin-bottom: 32px;
    }

    .header-left h1 {
        font-size: 28px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .header-left p {
        color: #475569;
        font-size: 14px;
        margin: 0;
    }

    /* Section Container */
    .section-container {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: #0F172A;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 255, 217, 0.15) 100%);
        border-radius: 12px;
        border-left: 4px solid;
    }

    .section-title.motivation {
        border-left-color: #F59E0B;
    }

    .section-title.shorts {
        border-left-color: #EC4899;
    }

    .section-title.youtube {
        border-left-color: #DC2626;
    }

    .section-title.entertainment {
        border-left-color: #8B5CF6;
    }

    .section-title.coaching {
        border-left-color: #06B6D4;
    }

    .section-icon {
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
    }

    .section-title.shorts .section-icon {
        background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%);
    }

    .section-title.youtube .section-icon {
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
    }

    .section-title.entertainment .section-icon {
        background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%);
    }

    .section-title.coaching .section-icon {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-mint) 100%);
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    .shorts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .content-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 255, 217, 0.08) 100%);
        border-radius: 12px;
        border: 2px solid rgba(179, 255, 179, 0.3);
        padding: 16px;
        box-shadow: 0 4px 12px rgba(179, 217, 255, 0.15);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .content-card:hover {
        box-shadow: 0 8px 24px rgba(179, 217, 255, 0.25);
        transform: translateY(-4px);
        border-color: rgba(179, 255, 179, 0.6);
        background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(179, 255, 217, 0.15) 100%);
    }

    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 12px;
        background: #000;
    }

    .shorts-container {
        position: relative;
        width: 100%;
        padding-bottom: 177.78%;
        height: 0;
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 12px;
        background: #000;
    }

    .video-container iframe,
    .shorts-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 10px;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .platform-badge {
        display: inline-block;
        padding: 4px 10px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        border: 1px solid rgba(37, 99, 235, 0.3);
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        color: #2563EB;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .platform-badge.youtube {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        border-color: rgba(220, 38, 38, 0.3);
        color: #991b1b;
    }

    .platform-badge.shorts {
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%);
        border-color: rgba(236, 72, 153, 0.3);
        color: #831843;
    }

    .content-title {
        font-size: 14px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .content-description {
        font-size: 12px;
        color: #475569;
        margin-bottom: 12px;
        flex: 1;
        line-height: 1.4;
    }

    .content-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(179, 255, 179, 0.2);
    }

    .content-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-btn {
        flex: 1;
        padding: 10px 12px;
        border: none;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-width: 80px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.2px;
    }

    .btn-share {
        background: linear-gradient(135deg, var(--soft-green) 0%, #B3FFB3 100%);
        color: #0F172A;
        border: 2px solid rgba(179, 255, 179, 0.6);
    }

    .btn-share:hover {
        box-shadow: 0 4px 12px rgba(179, 255, 179, 0.3);
        transform: translateY(-2px);
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .pagination-btn {
        padding: 10px 16px;
        border: 2px solid rgba(179, 255, 179, 0.5);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 255, 217, 0.1) 100%);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        color: #0F172A;
        transition: all 0.3s;
    }

    .pagination-btn:hover {
        background: linear-gradient(135deg, var(--soft-mint) 0%, var(--soft-blue) 100%);
        border-color: rgba(179, 255, 179, 0.8);
        transform: translateY(-2px);
    }

    .pagination-btn.active {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-mint) 100%);
        border-color: rgba(179, 255, 179, 0.8);
        color: #0F172A;
        font-weight: 700;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-info {
        font-size: 12px;
        color: #6B7280;
        font-weight: 600;
    }

    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.15) 0%, rgba(179, 217, 255, 0.15) 100%);
        border-radius: 12px;
        border: 2px solid rgba(179, 255, 179, 0.3);
        padding: 16px;
        margin-bottom: 32px;
        font-size: 13px;
        color: #0F172A;
        line-height: 1.6;
    }

    .info-box strong {
        color: #0F172A;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-section h1 {
            font-size: 20px;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .shorts-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .section-title {
            font-size: 16px;
        }
    }
</style>

<!-- Header Section -->
<div class="header-section">
    <div class="header-left">
        <h1>✨ Motivation & Entertainment</h1>
        <p>Discover inspiring content, entertainment, and expert coaching advice. Reduce social media usage with curated videos right here!</p>
    </div>
</div>

<!-- Info Box -->
<div class="info-box">
    <strong>💡 Tip:</strong> All videos play directly on this page. You can share your favorite content with others. All content is curated to provide motivation, entertainment, and valuable coaching advice. Use pagination to browse through more content!
</div>

<!-- YouTube Shorts Section -->
<div class="section-container">
    <div class="section-title shorts">
        <div class="section-icon">📱</div>
        <span>YouTube Shorts - Quick Motivation</span>
    </div>

    <div class="shorts-grid" id="shortsGrid">
        <!-- Shorts will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousShortsPage()" id="prevShortsBtn">← Previous</button>
        <span class="pagination-info" id="shortsPageInfo">Page 1 of 5</span>
        <button class="pagination-btn" onclick="nextShortsPage()" id="nextShortsBtn">Next →</button>
    </div>
</div>

<!-- Motivation Section -->
<div class="section-container">
    <div class="section-title motivation">
        <div class="section-icon">🚀</div>
        <span>Motivation & Inspiration</span>
    </div>

    <div class="content-grid" id="motivationGrid">
        <!-- Motivation videos will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousMotivationPage()" id="prevMotivationBtn">← Previous</button>
        <span class="pagination-info" id="motivationPageInfo">Page 1 of 3</span>
        <button class="pagination-btn" onclick="nextMotivationPage()" id="nextMotivationBtn">Next →</button>
    </div>
</div>

<!-- YouTube Content Section -->
<div class="section-container">
    <div class="section-title youtube">
        <div class="section-icon">📺</div>
        <span>YouTube Channels</span>
    </div>

    <div class="content-grid" id="youtubeGrid">
        <!-- YouTube videos will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousYoutubePage()" id="prevYoutubeBtn">← Previous</button>
        <span class="pagination-info" id="youtubePageInfo">Page 1 of 3</span>
        <button class="pagination-btn" onclick="nextYoutubePage()" id="nextYoutubeBtn">Next →</button>
    </div>
</div>

<!-- Entertainment Section -->
<div class="section-container">
    <div class="section-title entertainment">
        <div class="section-icon">🎭</div>
        <span>Entertainment & Fun</span>
    </div>

    <div class="content-grid" id="entertainmentGrid">
        <!-- Entertainment videos will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousEntertainmentPage()" id="prevEntertainmentBtn">← Previous</button>
        <span class="pagination-info" id="entertainmentPageInfo">Page 1 of 3</span>
        <button class="pagination-btn" onclick="nextEntertainmentPage()" id="nextEntertainmentBtn">Next →</button>
    </div>
</div>

<!-- Coaching & Advice Section -->
<div class="section-container">
    <div class="section-title coaching">
        <div class="section-icon">🎯</div>
        <span>Coaching & Expert Advice</span>
    </div>

    <div class="content-grid" id="coachingGrid">
        <!-- Coaching videos will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousCoachingPage()" id="prevCoachingBtn">← Previous</button>
        <span class="pagination-info" id="coachingPageInfo">Page 1 of 3</span>
        <button class="pagination-btn" onclick="nextCoachingPage()" id="nextCoachingBtn">Next →</button>
    </div>
</div>

<!-- Hindi Content Section -->
<div class="section-container">
    <div class="section-title motivation">
        <div class="section-icon">🇮🇳</div>
        <span>हिंदी सामग्री - Hindi Content</span>
    </div>

    <div class="content-grid" id="hindiGrid">
        <!-- Hindi videos will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousHindiPage()" id="prevHindiBtn">← Previous</button>
        <span class="pagination-info" id="hindiPageInfo">Page 1 of 3</span>
        <button class="pagination-btn" onclick="nextHindiPage()" id="nextHindiBtn">Next →</button>
    </div>
</div>

<!-- Indian Culture & Heritage Section -->
<div class="section-container">
    <div class="section-title entertainment">
        <div class="section-icon">🏛️</div>
        <span>भारतीय संस्कृति - Indian Culture & Heritage</span>
    </div>

    <div class="content-grid" id="indianGrid">
        <!-- Indian videos will be populated here -->
    </div>

    <div class="pagination-container">
        <button class="pagination-btn" onclick="previousIndianPage()" id="prevIndianBtn">← Previous</button>
        <span class="pagination-info" id="indianPageInfo">Page 1 of 3</span>
        <button class="pagination-btn" onclick="nextIndianPage()" id="nextIndianBtn">Next →</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Video data
    const shortsData = [
        { id: 'dQw4w9WgXcQ', title: 'Daily Motivation', desc: 'Quick motivation boost' },
        { id: 'ZXsQAXx_ao0', title: 'Success Tips', desc: 'Success mindset tips' },
        { id: 'tYzMGcUty6s', title: 'Goal Setting', desc: 'Quick goal setting tips' },
        { id: 'jNQXAC9IVRw', title: 'Productivity Hack', desc: 'Boost your productivity' },
        { id: '9bZkp7q19f0', title: 'Confidence Boost', desc: 'Build your confidence' },
        { id: 'kffacxfA7g4', title: 'Mindfulness', desc: 'Quick mindfulness exercise' },
        { id: 'aqz-KE-bpKQ', title: 'Positive Vibes', desc: 'Positive energy boost' },
        { id: 'OPf0YbXqDm0', title: 'Motivation Quote', desc: 'Inspiring quote' },
        { id: 'dQw4w9WgXcQ', title: 'Success Story', desc: 'Real success story' },
        { id: 'ZXsQAXx_ao0', title: 'Life Lesson', desc: 'Important life lesson' },
        { id: 'tYzMGcUty6s', title: 'Dream Big', desc: 'Dream big motivation' },
        { id: 'jNQXAC9IVRw', title: 'Take Action', desc: 'Action-taking tips' },
        { id: '9bZkp7q19f0', title: 'Overcome Fear', desc: 'Overcome your fears' },
        { id: 'kffacxfA7g4', title: 'Stay Focused', desc: 'Focus techniques' },
        { id: 'aqz-KE-bpKQ', title: 'Never Give Up', desc: 'Persistence motivation' },
    ];

    const motivationData = [
        { id: 'dQw4w9WgXcQ', title: 'Daily Motivation Boost', desc: 'Start your day with powerful motivational talks' },
        { id: 'ZXsQAXx_ao0', title: 'Success Mindset', desc: 'Learn the mindset of successful people' },
        { id: 'tYzMGcUty6s', title: 'Goal Setting Strategies', desc: 'Discover proven strategies for setting goals' },
        { id: 'jNQXAC9IVRw', title: 'Personal Growth', desc: 'Transform your life with personal growth' },
        { id: '9bZkp7q19f0', title: 'Overcome Obstacles', desc: 'Learn to overcome any obstacle' },
        { id: 'kffacxfA7g4', title: 'Build Confidence', desc: 'Build unshakeable confidence' },
        { id: 'aqz-KE-bpKQ', title: 'Dream Achievement', desc: 'Achieve your biggest dreams' },
        { id: 'OPf0YbXqDm0', title: 'Success Principles', desc: 'Universal principles of success' },
        { id: 'dQw4w9WgXcQ', title: 'Motivation Daily', desc: 'Daily dose of motivation' },
    ];

    const youtubeData = [
        { id: 'videoseries?list=PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf', title: 'Business & Entrepreneurship', desc: 'In-depth business strategies' },
        { id: 'jNQXAC9IVRw', title: 'Personal Development', desc: 'Self-improvement tutorials' },
        { id: '9bZkp7q19f0', title: 'Tech & Innovation', desc: 'Latest technology trends' },
        { id: 'dQw4w9WgXcQ', title: 'Leadership Skills', desc: 'Become a great leader' },
        { id: 'ZXsQAXx_ao0', title: 'Communication Mastery', desc: 'Master communication skills' },
        { id: 'tYzMGcUty6s', title: 'Time Management', desc: 'Manage your time effectively' },
        { id: 'kffacxfA7g4', title: 'Problem Solving', desc: 'Solve problems creatively' },
        { id: 'aqz-KE-bpKQ', title: 'Decision Making', desc: 'Make better decisions' },
        { id: 'OPf0YbXqDm0', title: 'Strategic Planning', desc: 'Plan your success' },
    ];

    const entertainmentData = [
        { id: 'kffacxfA7g4', title: 'Comedy & Humor', desc: 'Funny videos to lighten your mood' },
        { id: 'aqz-KE-bpKQ', title: 'Amazing Moments', desc: 'Incredible moments from around the world' },
        { id: 'OPf0YbXqDm0', title: 'Music & Vibes', desc: 'Relaxing music and good vibes' },
        { id: 'dQw4w9WgXcQ', title: 'Funny Fails', desc: 'Hilarious funny moments' },
        { id: 'ZXsQAXx_ao0', title: 'Talent Showcase', desc: 'Amazing talent performances' },
        { id: 'tYzMGcUty6s', title: 'Nature Beauty', desc: 'Beautiful nature moments' },
        { id: 'jNQXAC9IVRw', title: 'Travel Adventures', desc: 'Exciting travel adventures' },
        { id: '9bZkp7q19f0', title: 'Feel Good Stories', desc: 'Heartwarming feel-good stories' },
        { id: 'kffacxfA7g4', title: 'Inspiring Acts', desc: 'Inspiring acts of kindness' },
    ];

    const coachingData = [
        { id: 'ZXsQAXx_ao0', title: 'Life Coaching', desc: 'Professional life coaching strategies' },
        { id: 'tYzMGcUty6s', title: 'Career Development', desc: 'Expert career growth advice' },
        { id: 'dQw4w9WgXcQ', title: 'Financial Coaching', desc: 'Money management and investing' },
        { id: '9bZkp7q19f0', title: 'Health & Wellness', desc: 'Expert health and wellness guidance' },
        { id: 'jNQXAC9IVRw', title: 'Relationship Coaching', desc: 'Build better relationships' },
        { id: 'kffacxfA7g4', title: 'Mental Health', desc: 'Mental health and mindfulness' },
        { id: 'aqz-KE-bpKQ', title: 'Stress Management', desc: 'Manage stress effectively' },
        { id: 'OPf0YbXqDm0', title: 'Work-Life Balance', desc: 'Achieve work-life balance' },
        { id: 'dQw4w9WgXcQ', title: 'Habit Formation', desc: 'Build powerful habits' },
    ];

    const hindiData = [
        { id: 'dQw4w9WgXcQ', title: 'हिंदी प्रेरणा', desc: 'दैनिक प्रेरणा और सफलता के सूत्र' },
        { id: 'ZXsQAXx_ao0', title: 'सफलता की कहानी', desc: 'सफल लोगों की प्रेरणादायक कहानियां' },
        { id: 'tYzMGcUty6s', title: 'जीवन सीख', desc: 'जीवन के महत्वपूर्ण सबक' },
        { id: 'jNQXAC9IVRw', title: 'आत्मविश्वास बढ़ाएं', desc: 'अपने आत्मविश्वास को बढ़ान�� के तरीके' },
        { id: '9bZkp7q19f0', title: 'व्यावसायिक सलाह', desc: 'व्यापार और करियर की सलाह' },
        { id: 'kffacxfA7g4', title: 'मानसिक शांति', desc: 'मानसिक शांति और ध्यान' },
        { id: 'aqz-KE-bpKQ', title: 'परिवार और रिश्ते', desc: 'परिवार के रिश्तों को मजबूत करें' },
        { id: 'OPf0YbXqDm0', title: 'स्वास्थ्य और फिटनेस', desc: 'स्वास्थ्य और फिटनेस के सुझाव' },
        { id: 'dQw4w9WgXcQ', title: 'पैसे की बुद्धिमत्ता', desc: 'पैसे को समझें और बचाएं' },
    ];

    const indianData = [
        { id: 'ZXsQAXx_ao0', title: 'भारतीय संस्कृति', desc: 'भारतीय संस्कृति और परंपराओं की जानकारी' },
        { id: 'tYzMGcUty6s', title: 'भारतीय इतिहास', desc: 'भारत के महान इतिहास की कहानियां' },
        { id: 'dQw4w9WgXcQ', title: 'योग और आयुर्वेद', desc: 'प्राचीन भार��ीय योग और आयुर्वेद' },
        { id: 'jNQXAC9IVRw', title: 'भारतीय दर्शन', desc: 'भारतीय दर्शन और आध्यात्मिकता' },
        { id: '9bZkp7q19f0', title: 'भारतीय कला', desc: 'भारतीय कला और संगीत की परंपरा' },
        { id: 'kffacxfA7g4', title: 'भारतीय खाना', desc: 'भारतीय खाने की विविधता और स्वाद' },
        { id: 'aqz-KE-bpKQ', title: 'भारतीय त्योहार', desc: 'भारत के रंगीन त्योहारों की जानकारी' },
        { id: 'OPf0YbXqDm0', title: 'भारतीय उद्यमी', desc: 'सफल भारतीय उद्यमियों की कहानियां' },
        { id: 'dQw4w9WgXcQ', title: 'भारतीय विज्ञान', desc: 'भारत के वैज्ञानिक योगदान' },
    ];

    // Pagination state
    let currentPages = {
        shorts: 1,
        motivation: 1,
        youtube: 1,
        entertainment: 1,
        coaching: 1
    };

    const itemsPerPage = 3;

    function createVideoCard(video, isShort = false) {
        const containerClass = isShort ? 'shorts-container' : 'video-container';
        const embedUrl = isShort 
            ? `https://www.youtube.com/embed/${video.id}?rel=0`
            : `https://www.youtube.com/embed/${video.id}`;
        
        return `
            <div class="content-card">
                <div class="content-header">
                    <span class="platform-badge ${isShort ? 'shorts' : 'youtube'}">${isShort ? 'Shorts' : 'YouTube'}</span>
                </div>
                <div class="${containerClass}">
                    <iframe src="${embedUrl}" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <div class="content-title">${video.title}</div>
                <div class="content-description">${video.desc}</div>
                <div class="content-meta">
                    <span>${isShort ? '📱 Short Video' : '📺 Video Content'}</span>
                    <span>${isShort ? '1-3 min' : '10-20 min'}</span>
                </div>
                <div class="content-actions">
                    <button class="action-btn btn-share" onclick="shareContent('${video.title}')">Share</button>
                </div>
            </div>
        `;
    }

    function renderVideos(data, gridId, page, isShort = false) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const videos = data.slice(start, end);
        
        const grid = document.getElementById(gridId);
        grid.innerHTML = videos.map(v => createVideoCard(v, isShort)).join('');
    }

    function updatePagination(section, totalPages) {
        const prevBtn = document.getElementById(`prev${section}Btn`);
        const nextBtn = document.getElementById(`next${section}Btn`);
        const pageInfo = document.getElementById(`${section.toLowerCase()}PageInfo`);
        const currentPage = currentPages[section.toLowerCase()];

        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    }

    function changePage(section, direction, data, gridId, isShort = false) {
        const totalPages = Math.ceil(data.length / itemsPerPage);
        const key = section.toLowerCase();
        
        if (direction === 'next' && currentPages[key] < totalPages) {
            currentPages[key]++;
        } else if (direction === 'prev' && currentPages[key] > 1) {
            currentPages[key]--;
        }

        renderVideos(data, gridId, currentPages[key], isShort);
        updatePagination(section, totalPages);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Shorts pagination
    function nextShortsPage() { changePage('Shorts', 'next', shortsData, 'shortsGrid', true); }
    function previousShortsPage() { changePage('Shorts', 'prev', shortsData, 'shortsGrid', true); }

    // Motivation pagination
    function nextMotivationPage() { changePage('Motivation', 'next', motivationData, 'motivationGrid'); }
    function previousMotivationPage() { changePage('Motivation', 'prev', motivationData, 'motivationGrid'); }

    // YouTube pagination
    function nextYoutubePage() { changePage('Youtube', 'next', youtubeData, 'youtubeGrid'); }
    function previousYoutubePage() { changePage('Youtube', 'prev', youtubeData, 'youtubeGrid'); }

    // Entertainment pagination
    function nextEntertainmentPage() { changePage('Entertainment', 'next', entertainmentData, 'entertainmentGrid'); }
    function previousEntertainmentPage() { changePage('Entertainment', 'prev', entertainmentData, 'entertainmentGrid'); }

    // Coaching pagination
    function nextCoachingPage() { changePage('Coaching', 'next', coachingData, 'coachingGrid'); }
    function previousCoachingPage() { changePage('Coaching', 'prev', coachingData, 'coachingGrid'); }

    // Hindi pagination
    function nextHindiPage() { changePage('Hindi', 'next', hindiData, 'hindiGrid'); }
    function previousHindiPage() { changePage('Hindi', 'prev', hindiData, 'hindiGrid'); }

    // Indian pagination
    function nextIndianPage() { changePage('Indian', 'next', indianData, 'indianGrid'); }
    function previousIndianPage() { changePage('Indian', 'prev', indianData, 'indianGrid'); }

    function shareContent(title) {
        if (navigator.share) {
            navigator.share({
                title: 'Motivation & Entertainment',
                text: `Check out this amazing content: ${title}`,
                url: window.location.href
            }).catch(err => console.log('Error sharing:', err));
        } else {
            alert(`Share this content: ${title}\n\nURL: ${window.location.href}`);
        }
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        renderVideos(shortsData, 'shortsGrid', 1, true);
        renderVideos(motivationData, 'motivationGrid', 1);
        renderVideos(youtubeData, 'youtubeGrid', 1);
        renderVideos(entertainmentData, 'entertainmentGrid', 1);
        renderVideos(coachingData, 'coachingGrid', 1);

        updatePagination('Shorts', Math.ceil(shortsData.length / itemsPerPage));
        updatePagination('Motivation', Math.ceil(motivationData.length / itemsPerPage));
        updatePagination('Youtube', Math.ceil(youtubeData.length / itemsPerPage));
        updatePagination('Entertainment', Math.ceil(entertainmentData.length / itemsPerPage));
        updatePagination('Coaching', Math.ceil(coachingData.length / itemsPerPage));
    });
</script>
@endsection
