@extends('layouts.app')

@section('page-title', 'Add Team Member')

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

    .create-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Back Button */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: linear-gradient(135deg, rgba(217, 179, 255, 0.2) 0%, rgba(179, 217, 255, 0.2) 100%);
        color: #6b21a8;
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
        border: 1px solid rgba(217, 179, 255, 0.3);
    }

    .back-button:hover {
        background: linear-gradient(135deg, rgba(217, 179, 255, 0.3) 0%, rgba(179, 217, 255, 0.3) 100%);
        transform: translateX(-3px);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(217, 179, 255, 0.2) 50%, rgba(179, 217, 255, 0.15) 100%);
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 28px;
        border: 1px solid rgba(179, 255, 179, 0.3);
        box-shadow: 0 4px 20px rgba(179, 255, 179, 0.15);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-icon {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        padding: 16px;
        border-radius: 16px;
        font-size: 32px;
    }

    .header-text h1 {
        font-size: 26px;
        color: #0F172A;
        font-weight: 700;
        margin: 0 0 6px 0;
    }

    .header-text p {
        color: #6B7280;
        font-size: 14px;
        margin: 0;
    }

    /* Form Layout */
    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Form Cards */
    .form-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 217, 255, 0.08) 100%);
        border-radius: 20px;
        padding: 24px;
        border: 1px solid rgba(179, 217, 255, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.1);
        margin-bottom: 20px;
    }

    .form-card.green {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 255, 179, 0.08) 100%);
        border-color: rgba(179, 255, 179, 0.3);
    }

    .form-card.purple {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(217, 179, 255, 0.08) 100%);
        border-color: rgba(217, 179, 255, 0.3);
    }

    .form-card.orange {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 217, 179, 0.08) 100%);
        border-color: rgba(255, 217, 179, 0.3);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid rgba(179, 217, 255, 0.2);
    }

    .card-header-icon {
        padding: 8px;
        border-radius: 10px;
        font-size: 18px;
    }

    .card-header-icon.blue { background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%); }
    .card-header-icon.green { background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); }
    .card-header-icon.purple { background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); }
    .card-header-icon.orange { background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); }

    .card-header h3 {
        font-size: 16px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        font-weight: 600;
        color: #0F172A;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid rgba(179, 217, 255, 0.4);
        border-radius: 12px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 217, 255, 0.05) 100%);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--soft-purple);
        box-shadow: 0 0 0 3px rgba(217, 179, 255, 0.2);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 500px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .error-text {
        color: #DC2626;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    /* Quick Role Selection */
    .role-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .role-btn {
        padding: 8px 16px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: #6B7280;
    }

    .role-btn:hover {
        border-color: var(--soft-purple);
        color: #6b21a8;
    }

    .role-btn.active {
        border-color: var(--soft-purple);
        background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%);
        color: #6b21a8;
    }

    .role-btn.developer { border-color: var(--soft-blue); }
    .role-btn.developer.active { background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%); color: #0c4a6e; }

    .role-btn.designer { border-color: var(--soft-pink); }
    .role-btn.designer.active { background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%); color: #9d174d; }

    .role-btn.manager { border-color: var(--soft-orange); }
    .role-btn.manager.active { background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); color: #9a3412; }

    /* Avatar Preview */
    .avatar-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        color: #6b21a8;
        margin: 0 auto 12px;
        border: 3px solid white;
        box-shadow: 0 4px 16px rgba(217, 179, 255, 0.3);
    }

    .avatar-name {
        font-size: 14px;
        color: #6B7280;
    }

    /* Status Toggle */
    .status-toggle {
        display: flex;
        gap: 12px;
    }

    .status-option {
        flex: 1;
        padding: 14px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
    }

    .status-option:hover {
        border-color: var(--soft-green);
    }

    .status-option.active {
        border-color: var(--soft-green);
        background: linear-gradient(135deg, rgba(179, 255, 179, 0.2) 0%, rgba(179, 255, 217, 0.2) 100%);
    }

    .status-option.inactive.active {
        border-color: var(--soft-orange);
        background: linear-gradient(135deg, rgba(255, 217, 179, 0.2) 0%, rgba(255, 204, 179, 0.2) 100%);
    }

    .status-option .status-icon {
        font-size: 24px;
        margin-bottom: 6px;
    }

    .status-option .status-label {
        font-size: 13px;
        font-weight: 600;
        color: #0F172A;
    }

    /* Skills Tags */
    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .skill-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #0c4a6e;
    }

    .skill-tag .remove-skill {
        cursor: pointer;
        font-size: 14px;
        opacity: 0.7;
    }

    .skill-tag .remove-skill:hover {
        opacity: 1;
    }

    .add-skill-btn {
        padding: 6px 12px;
        border: 2px dashed var(--soft-blue);
        background: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #0c4a6e;
        cursor: pointer;
        transition: all 0.2s;
    }

    .add-skill-btn:hover {
        background: rgba(179, 217, 255, 0.2);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-submit {
        flex: 1;
        padding: 14px 24px;
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        border: 1px solid rgba(179, 255, 179, 0.5);
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        color: #15803d;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(179, 255, 179, 0.4);
    }

    .btn-cancel {
        padding: 14px 24px;
        background: linear-gradient(135deg, rgba(230, 217, 255, 0.3) 0%, rgba(217, 179, 255, 0.2) 100%);
        border: 1px solid rgba(217, 179, 255, 0.4);
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        color: #6b21a8;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, rgba(230, 217, 255, 0.5) 0%, rgba(217, 179, 255, 0.4) 100%);
    }

    /* Tips Section */
    .tips-card {
        background: linear-gradient(135deg, rgba(255, 217, 179, 0.15) 0%, rgba(255, 204, 179, 0.1) 100%);
        border: 1px solid rgba(255, 217, 179, 0.3);
        border-radius: 16px;
        padding: 18px;
    }

    .tips-card h4 {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #9a3412;
        margin: 0 0 12px 0;
    }

    .tips-card ul {
        margin: 0;
        padding: 0 0 0 18px;
        font-size: 13px;
        color: #6B7280;
    }

    .tips-card li {
        margin-bottom: 8px;
    }
</style>

<div class="create-container">
    <a href="/admin/team-members" class="back-button">
        ← Back to Team Members
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <span class="header-icon">👤</span>
            <div class="header-text">
                <h1>Add New Team Member</h1>
                <p>Quick and easy way to add a new member to your team — fill in the details below</p>
            </div>
        </div>
    </div>

    <form method="POST" action="/admin/team-members" id="memberForm">
        @csrf
        
        <div class="form-grid">
            <!-- Main Form Column -->
            <div class="main-column">
                <!-- Personal Information -->
                <div class="form-card">
                    <div class="card-header">
                        <span class="card-header-icon blue">👤</span>
                        <h3>Personal Information</h3>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">
                            <span>📝</span> Full Name *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g., John Doe" oninput="updateAvatarPreview()">
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">
                                <span>📧</span> Email Address *
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="e.g., john@example.com">
                            @error('email')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <span>📱</span> Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g., +1 234 567 890">
                            @error('phone')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Role & Department -->
                <div class="form-card purple">
                    <div class="card-header">
                        <span class="card-header-icon purple">💼</span>
                        <h3>Role & Department</h3>
                    </div>

                    <div class="form-group">
                        <label>Quick Select Role</label>
                        <div class="role-selector">
                            <button type="button" class="role-btn developer" onclick="selectRole('Developer')">💻 Developer</button>
                            <button type="button" class="role-btn designer" onclick="selectRole('Designer')">🎨 Designer</button>
                            <button type="button" class="role-btn manager" onclick="selectRole('Project Manager')">📊 Manager</button>
                            <button type="button" class="role-btn" onclick="selectRole('QA Engineer')">🧪 QA</button>
                            <button type="button" class="role-btn" onclick="selectRole('DevOps')">⚙️ DevOps</button>
                            <button type="button" class="role-btn" onclick="selectRole('Admin')">🔐 Admin</button>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role">
                                <span>🎯</span> Role/Position *
                            </label>
                            <input type="text" id="role" name="role" value="{{ old('role') }}" required placeholder="e.g., Senior Developer">
                            @error('role')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="department">
                                <span>🏢</span> Department
                            </label>
                            <select id="department" name="department">
                                <option value="">Select Department</option>
                                <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="Design" {{ old('department') == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                                <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>Human Resources</option>
                                <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                            </select>
                            @error('department')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bio">
                            <span>📄</span> Short Bio
                        </label>
                        <textarea id="bio" name="bio" placeholder="A brief description about the team member...">{{ old('bio') }}</textarea>
                        @error('bio')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="form-card green">
                    <div class="card-header">
                        <span class="card-header-icon green">✅</span>
                        <h3>Status</h3>
                    </div>

                    <input type="hidden" id="status" name="status" value="{{ old('status', 'active') }}">
                    
                    <div class="status-toggle">
                        <div class="status-option active" data-status="active" onclick="selectStatus('active')">
                            <div class="status-icon">✅</div>
                            <div class="status-label">Active</div>
                        </div>
                        <div class="status-option inactive" data-status="inactive" onclick="selectStatus('inactive')">
                            <div class="status-icon">⏸️</div>
                            <div class="status-label">Inactive</div>
                        </div>
                    </div>
                    @error('status')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn-submit">
                        <span>✅</span> Add Team Member
                    </button>
                    <a href="/admin/team-members" class="btn-cancel">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="sidebar-column">
                <!-- Avatar Preview -->
                <div class="form-card">
                    <div class="card-header">
                        <span class="card-header-icon orange">🖼️</span>
                        <h3>Preview</h3>
                    </div>
                    
                    <div class="avatar-section">
                        <div class="avatar-preview" id="avatarPreview">
                            <span id="avatarInitials">?</span>
                        </div>
                        <div class="avatar-name" id="previewName">Enter name above</div>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="form-card">
                    <div class="card-header">
                        <span class="card-header-icon blue">🛠️</span>
                        <h3>Skills</h3>
                    </div>

                    <div class="skills-container" id="skillsContainer">
                        <!-- Skills will be added here -->
                    </div>
                    <input type="hidden" id="skills" name="skills" value="{{ old('skills') }}">
                    <button type="button" class="add-skill-btn" onclick="addSkill()">➕ Add Skill</button>
                </div>

                <!-- Tips Card -->
                <div class="tips-card">
                    <h4>💡 Quick Tips</h4>
                    <ul>
                        <li>Use the quick select buttons to choose common roles</li>
                        <li>Add skills to help with project assignments</li>
                        <li>Active members can be assigned to projects</li>
                        <li>All fields marked with * are required</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let skills = [];

    function updateAvatarPreview() {
        const name = document.getElementById('name').value.trim();
        const avatarInitials = document.getElementById('avatarInitials');
        const previewName = document.getElementById('previewName');
        
        if (name) {
            const parts = name.split(' ');
            let initials = '';
            if (parts.length >= 2) {
                initials = parts[0][0].toUpperCase() + parts[1][0].toUpperCase();
            } else if (parts.length === 1) {
                initials = parts[0].substring(0, 2).toUpperCase();
            }
            avatarInitials.textContent = initials;
            previewName.textContent = name;
        } else {
            avatarInitials.textContent = '?';
            previewName.textContent = 'Enter name above';
        }
    }

    function selectRole(role) {
        document.getElementById('role').value = role;
        
        // Update button states
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.includes(role) || btn.textContent.includes(role.split(' ')[0])) {
                btn.classList.add('active');
            }
        });
    }

    function selectStatus(status) {
        document.getElementById('status').value = status;
        
        // Update option states
        document.querySelectorAll('.status-option').forEach(option => {
            option.classList.remove('active');
            if (option.getAttribute('data-status') === status) {
                option.classList.add('active');
            }
        });
    }

    function addSkill() {
        const skill = prompt('Enter a skill:');
        if (skill && skill.trim()) {
            skills.push(skill.trim());
            updateSkillsDisplay();
        }
    }

    function removeSkill(index) {
        skills.splice(index, 1);
        updateSkillsDisplay();
    }

    function updateSkillsDisplay() {
        const container = document.getElementById('skillsContainer');
        const hiddenInput = document.getElementById('skills');
        
        container.innerHTML = skills.map((skill, index) => `
            <span class="skill-tag">
                ${skill}
                <span class="remove-skill" onclick="removeSkill(${index})">×</span>
            </span>
        `).join('');
        
        hiddenInput.value = JSON.stringify(skills);
    }

    // Initialize status on page load
    document.addEventListener('DOMContentLoaded', function() {
        const currentStatus = document.getElementById('status').value || 'active';
        selectStatus(currentStatus);
        
        // Load existing skills if any
        const existingSkills = document.getElementById('skills').value;
        if (existingSkills) {
            try {
                skills = JSON.parse(existingSkills);
                updateSkillsDisplay();
            } catch (e) {}
        }
    });
</script>
@endsection
