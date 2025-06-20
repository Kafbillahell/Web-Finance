@extends('layouts.default')

@section('style')
<style>
    .profile-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .profile-card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    /* Avatar Styles */
    .avatar-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f8f9fa;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
    }

    .avatar-badge {
        background: #007bff;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        bottom: 5px;
        right: 5px;
        font-size: 0.8rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .avatar-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }

    /* Profile Info Styles */
    .profile-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .profile-info h4 {
        color: #333;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .info-icon {
        width: 20px;
        margin-right: 0.75rem;
        color: #6c757d;
        text-align: center;
    }

    .info-label {
        font-weight: 500;
        color: #495057;
        min-width: 80px;
        margin-right: 0.5rem;
    }

    .info-value {
        color: #6c757d;
        flex: 1;
    }

    /* Button Styles */
    .btn-edit-profile {
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
    }

    .btn-edit-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-edit-profile:active {
        transform: translateY(0);
    }

    /* Role Badge */
    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .role-badge.admin {
        background: #dc3545;
        color: white;
    }

    .role-badge.user {
        background: #28a745;
        color: white;
    }

    .role-badge.moderator {
        background: #ffc107;
        color: #212529;
    }

    /* Success Message */
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        border: 1px solid transparent;
    }

    .alert-success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-dismissible {
        position: relative;
        padding-right: 3rem;
    }

    .alert-dismissible .close {
        position: absolute;
        top: 0.5rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    .alert-dismissible .close:hover {
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-container {
            margin: 1rem;
        }

        .profile-card {
            padding: 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
            &times;
        </button>
    </div>
    @endif

    <div class="profile-card">
        <div class="avatar-section">
            <div class="avatar-container">
                <img src="{{ asset('assets/avatars/' . ($user->avatar ?? 'avatar1.png')) }}"
                    alt="{{ $user->name }}" class="profile-avatar">
                <span class="avatar-badge">
                    <i class="fas fa-user"></i>
                </span>
            </div>
        </div>

        <div class="profile-info">
            <h4>{{ $user->name }}</h4>

            <div class="info-item">
                <i class="fas fa-envelope info-icon"></i>
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>

            <div class="info-item">
                <i class="fas fa-user-tag info-icon"></i>
                <span class="info-label">Role:</span>
                <span class="info-value">
                    <span class="role-badge {{ strtolower($user->role ?? 'user') }}">
                        {{ ucfirst($user->role ?? 'user') }}
                    </span>
                </span>
            </div>

            <div class="info-item">
                <i class="fas fa-calendar-alt info-icon"></i>
                <span class="info-label">Joined:</span>
                <span class="info-value">{{ $user->created_at->format('F j, Y') }}</span>
            </div>

            <div class="info-item">
                <i class="fas fa-clock info-icon"></i>
                <span class="info-label">Last Updated:</span>
                <span class="info-value">{{ $user->updated_at->format('F j, Y g:i A') }}</span>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>
</div>
@endsection