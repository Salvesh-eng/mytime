@extends('layouts.app')

@section('page-title', 'Create New User')

@section('content')
<style>
    .user-form-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 32px;
        margin-bottom: 20px;
    }

    .form-card h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-card p {
        color: #6B7280;
        font-size: 14px;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-group small {
        display: block;
        color: #6B7280;
        font-size: 12px;
        margin-top: 6px;
    }

    .password-hint {
        background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 13px;
        color: #0c4a6e;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .password-hint::before {
        content: 'ℹ️';
        font-size: 16px;
    }

    .role-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
    }

    .role-option {
        position: relative;
    }

    .role-option input[type="radio"] {
        display: none;
    }

    .role-option label {
        display: block;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
        margin: 0;
    }

    .role-option input[type="radio"]:checked + label {
        border-color: #2563EB;
        background-color: #f0f9ff;
        color: #2563EB;
        font-weight: 600;
    }

    .role-option label:hover {
        border-color: #2563EB;
        background-color: #f9fafb;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .form-actions button,
    .form-actions a {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .form-actions .btn-primary {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .form-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    .form-actions .btn-secondary {
        background-color: #e5e7eb;
        color: #0F172A;
    }

    .form-actions .btn-secondary:hover {
        background-color: #d1d5db;
    }

    .info-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #ecf8ff 100%);
        border: 1px solid #0ea5e9;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #0c4a6e;
        line-height: 1.6;
    }

    .info-box strong {
        color: #0369a1;
    }

    @media (max-width: 600px) {
        .form-card {
            padding: 20px;
        }

        .role-options {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<div class="user-form-container">
    <div class="form-card">
        <h2>👤 Create New User</h2>
        <p>Add a new team member to your organization</p>

        <div class="info-box">
            <strong>📋 Note:</strong> You can leave the password field blank and a secure password will be auto-generated for the new user.
        </div>

        <form method="POST" action="/admin/users" id="createUserForm">
            @csrf

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter user's full name" required autofocus>
                @error('name')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required>
                @error('email')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Leave blank for auto-generated password">
                <small>Minimum 8 characters. Leave blank to auto-generate a secure password.</small>
                @error('password')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label>User Role *</label>
                <div class="role-options">
                    <div class="role-option">
                        <input type="radio" id="role_user" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} required>
                        <label for="role_user">
                            <div style="font-size: 24px; margin-bottom: 8px;">👤</div>
                            <div style="font-weight: 600;">User</div>
                            <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">Regular team member</div>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="role_admin" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} required>
                        <label for="role_admin">
                            <div style="font-size: 24px; margin-bottom: 8px;">👑</div>
                            <div style="font-weight: 600;">Admin</div>
                            <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">Full system access</div>
                        </label>
                    </div>
                </div>
                @error('role')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">✓ Create User</button>
                <a href="/admin/users" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!name || !email) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
    });
</script>
@endsection
