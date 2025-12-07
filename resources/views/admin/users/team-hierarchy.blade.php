@extends('layouts.app')

@section('page-title', 'Team Hierarchy')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet" type="text/css" />

<style>
    .hierarchy-header {
        margin-bottom: 30px;
    }

    .hierarchy-header h1 {
        font-size: 28px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .hierarchy-header p {
        color: #6B7280;
        font-size: 14px;
    }

    .hierarchy-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
    }

    #network {
        width: 100%;
        height: 600px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #f9fafb;
    }

    .hierarchy-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .hierarchy-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #2563EB;
    }

    .hierarchy-card-header {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }

    .hierarchy-card-avatar {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }

    .hierarchy-card-info h3 {
        margin: 0;
        font-size: 14px;
        color: #0F172A;
        font-weight: 600;
    }

    .hierarchy-card-info p {
        margin: 4px 0 0 0;
        font-size: 12px;
        color: #6B7280;
    }

    .hierarchy-card-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
    }

    .stat {
        text-align: center;
    }

    .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #2563EB;
    }

    .stat-label {
        font-size: 11px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    .hierarchy-card-action {
        margin-top: 12px;
    }

    .hierarchy-card-action a {
        display: inline-block;
        padding: 8px 12px;
        background: #2563EB;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .hierarchy-card-action a:hover {
        background: #1d4ed8;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background: #f3f4f6;
        color: #0F172A;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .back-button:hover {
        background: #e5e7eb;
    }
</style>

<a href="{{ route('admin.users.index') }}" class="back-button">← Back to Users</a>

<div class="hierarchy-header">
    <h1>👥 Team Hierarchy</h1>
    <p>Organizational structure showing reporting relationships and team members</p>
</div>

<!-- Network Visualization -->
<div class="hierarchy-container">
    <h2 style="font-size: 16px; color: #0F172A; font-weight: 600; margin-bottom: 15px;">Organization Chart</h2>
    <div id="network"></div>
</div>

<!-- Team Members List -->
<div class="hierarchy-container">
    <h2 style="font-size: 16px; color: #0F172A; font-weight: 600; margin-bottom: 15px;">Team Members ({{ $users->count() }})</h2>
    <div class="hierarchy-list">
        @foreach($users as $user)
            <div class="hierarchy-card">
                <div class="hierarchy-card-header">
                    <div class="hierarchy-card-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="hierarchy-card-info">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->position ?? 'Team Member' }}</p>
                    </div>
                </div>
                <div class="hierarchy-card-stats">
                    <div class="stat">
                        <div class="stat-value">{{ $user->subordinates()->count() }}</div>
                        <div class="stat-label">Reports</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">{{ $user->projects()->count() }}</div>
                        <div class="stat-label">Projects</div>
                    </div>
                </div>
                <div class="hierarchy-card-action">
                    <a href="{{ route('admin.users.profile', $user) }}">View Profile</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    // Prepare data for network visualization
    const users = @json($users);
    const nodes = new vis.DataSet();
    const edges = new vis.DataSet();

    // Add nodes
    users.forEach(user => {
        nodes.add({
            id: user.id,
            label: user.name,
            title: user.position || 'Team Member',
            color: {
                background: '#2563EB',
                border: '#1d4ed8',
                highlight: {
                    background: '#1d4ed8',
                    border: '#0c4a6e'
                }
            },
            font: {
                color: 'white',
                size: 14,
                face: 'Segoe UI, sans-serif'
            },
            shape: 'box',
            margin: 10,
            widthConstraint: {
                maximum: 150
            }
        });
    });

    // Add edges (reporting relationships)
    users.forEach(user => {
        if (user.manager_id) {
            edges.add({
                from: user.manager_id,
                to: user.id,
                arrows: 'to',
                color: {
                    color: '#9CA3AF',
                    highlight: '#2563EB'
                },
                smooth: {
                    type: 'cubicBezier'
                }
            });
        }
    });

    // Create network
    const container = document.getElementById('network');
    const data = {
        nodes: nodes,
        edges: edges
    };

    const options = {
        physics: {
            enabled: true,
            stabilization: {
                iterations: 200
            },
            barnesHut: {
                gravitationalConstant: -30000,
                centralGravity: 0.3,
                springLength: 200,
                springConstant: 0.04
            }
        },
        layout: {
            hierarchical: {
                enabled: true,
                levelSeparation: 150,
                nodeSpacing: 100,
                direction: 'UD'
            }
        },
        interaction: {
            navigationButtons: true,
            keyboard: true,
            zoomView: true,
            dragView: true
        }
    };

    const network = new vis.Network(container, data, options);

    // Handle node clicks
    network.on('click', function(params) {
        if (params.nodes.length > 0) {
            const userId = params.nodes[0];
            window.location.href = '/admin/users/' + userId + '/profile';
        }
    });
</script>

@endsection
