@extends('layouts.app')

@section('title', 'Motivation / प्रेरणा')

@section('styles')
<style>
    .motivation-hero { padding: 18px; margin-bottom: 18px; border-radius: 8px; background: linear-gradient(90deg,#fff8eb,#f0f9ff); border-left:4px solid #f59e0b; }
    .videos-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(320px,1fr)); gap: 18px; }
    .video-card { background: #fff; padding: 14px; border-radius: 8px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
    .video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius:6px; }
    .video-wrapper iframe { position: absolute; top:0; left:0; width:100%; height:100%; border:0; }
    .video-title { margin-top:10px; font-weight:700; font-size:14px; color:#0f172a; }
    .video-title small { display:block; font-weight:600; color:#6b7280; margin-top:4px; font-size:12px; }
    .filter { margin-bottom:12px; display:flex; gap:8px; align-items:center; }
    .filter input { flex:1; padding:8px 10px; border:1px solid #e5e7eb; border-radius:6px; }
</style>
@endsection

@section('content')
<div class="dashboard-content">
    <div class="motivation-hero">
        <h2>Motivation / प्रेरणा</h2>
        <p style="margin-top:6px; color:#374151;">Collection of Hindi & English motivational videos, short talks and playlists. नीचे दिए गए वीडियो रोज़ प्रेरित करने के लिए।</p>
    </div>

    <div class="card">
        <div class="filter">
            <input id="video-filter" placeholder="Search videos — वीडियो खोजें (title in English or Hindi)" />
            <button class="btn btn-primary" onclick="resetFilter()">Reset</button>
        </div>

        <div class="videos-grid" id="videos-grid">
            @foreach($videos as $v)
            @php
                $dataTitle = strtolower($v['title_en'].' '.$v['title_hi']);
                $id = $v['id'];
            @endphp
            <div class="video-card" data-title="{{ $dataTitle }}">
                <div class="video-wrapper">
                    @if(str_starts_with($id, 'PLAYLIST:'))
                        @php $plist = substr($id, strlen('PLAYLIST:')); @endphp
                        <iframe loading="lazy" src="https://www.youtube.com/embed?listType=playlist&list={{ $plist }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <iframe loading="lazy" src="https://www.youtube.com/embed/{{ $id }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @endif
                </div>
                <div class="video-title">
                    {{ $v['title_en'] }}
                    <small>{{ $v['title_hi'] }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div style="margin-top:18px;" class="card">
        <h3>Tips / सुझाव</h3>
        <ul style="margin-top:8px; color:#374151;">
            <li>Replace the VIDEO_ID_* and PLAYLIST_ID_* values in <code>app/Http/Controllers/MotivationController.php</code> with actual YouTube video or playlist IDs you want to show.</li>
            <li>To add more videos, append to the <code>$videos</code> array in the controller.</li>
            <li>For playlists you can use embed URLs like: <code>PLAYLIST:PLxxxxxxxxxxxxxxxx</code> (controller uses the PLAYLIST: prefix).</li>
            <li>For channels or large lists consider using YouTube Data API server-side to fetch latest uploads (requires API key).</li>
        </ul>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // simple client-side filter
    const filterInput = document.getElementById('video-filter');
    const grid = document.getElementById('videos-grid');
    filterInput && filterInput.addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        Array.from(grid.children).forEach(card => {
            const t = card.getAttribute('data-title') || '';
            card.style.display = t.includes(q) ? '' : 'none';
        });
    });
    function resetFilter() {
        filterInput.value = '';
        filterInput.dispatchEvent(new Event('input'));
    }
</script>
@endsection
