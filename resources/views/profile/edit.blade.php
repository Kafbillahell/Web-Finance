@extends('layouts.default')

@section('style')
<style>
    .profile-edit-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .profile-edit-card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    .btn-submit {
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
    }

    /* Avatar Upload Styles */
    .avatar-upload-section {
        text-align: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .current-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        border: 3px solid #dee2e6;
    }

    .avatar-upload-input {
        display: none;
    }

    .avatar-upload-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .avatar-upload-btn:hover {
        background: #0056b3;
    }

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 1rem auto;
        display: none;
        border: 3px solid #007bff;
    }

    .avatar-info {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    /* Account Deletion Styles */
    .danger-zone {
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .danger-zone h5 {
        color: #e53e3e;
        margin-bottom: 1rem;
    }

    .btn-danger-outline {
        background: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-danger-outline:hover {
        background: #dc3545;
        color: white;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 2rem;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        position: relative;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-title {
        color: #dc3545;
        margin: 0;
    }

    .close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
    }

    .close:hover {
        color: #333;
    }

    .modal-body p {
        margin-bottom: 1rem;
        color: #666;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #5a6268;
    }

    .btn-confirm-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-confirm-delete:hover {
        background: #c82333;
    }
</style>
@endsection

@section('content')
<div class="profile-edit-container">
    <div class="profile-edit-card">
        <h2 class="text-center mb-4">Edit Profile</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Avatar Upload Section -->
            <div class="avatar-upload-section">
                <img src="{{ asset('assets/avatars/' . ($user->avatar ?? 'avatar1.png')) }}"
                    alt="Current Avatar" class="current-avatar" id="currentAvatar">
                <img src="#" alt="Avatar Preview" class="avatar-preview" id="avatarPreview">

                <div>
                    <input type="file" id="avatar" name="avatar" class="avatar-upload-input"
                        accept="image/jpeg,image/png,image/jpg,image/gif">
                    <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatar').click()">
                        <i class="fas fa-camera"></i> Choose Avatar
                    </button>
                </div>

                <div class="avatar-info">
                    <small>Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation"
                    name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary btn-submit">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </form>

        <!-- Danger Zone -->
        <div class="danger-zone">
            <h5><i class="fas fa-exclamation-triangle"></i> Danger Zone</h5>
            <p>Once you delete your account, there is no going back. This action cannot be undone.</p>
            <button type="button" class="btn-danger-outline" onclick="openDeleteModal()">
                <i class="fas fa-trash"></i> Delete Account
            </button>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Account Deletion</h5>
            <button type="button" class="close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Are you absolutely sure?</strong></p>
            <p>This action cannot be undone. This will permanently delete your account and remove all of your data from our servers.</p>
            <p>Please type <strong>{{ $user->email }}</strong> to confirm:</p>
            <input type="text" id="confirmEmail" class="form-control" placeholder="Enter your email">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn-confirm-delete" onclick="deleteAccount()" id="confirmDeleteBtn" disabled>
                Delete Account
            </button>
        </div>
    </div>
</div>

<script>
    // Avatar preview functionality
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const currentAvatar = document.getElementById('currentAvatar');
                const avatarPreview = document.getElementById('avatarPreview');

                currentAvatar.style.display = 'none';
                avatarPreview.src = e.target.result;
                avatarPreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Modal functionality
    function openDeleteModal() {
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.getElementById('confirmEmail').value = '';
        document.getElementById('confirmDeleteBtn').disabled = true;
    }

    // Enable delete button only when email matches
    document.getElementById('confirmEmail').addEventListener('input', function() {
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const userEmail = '{{ $user->email }}';

        if (this.value === userEmail) {
            confirmBtn.disabled = false;
        } else {
            confirmBtn.disabled = true;
        }
    });

    // Handle account deletion
    function deleteAccount() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("profile.delete") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    }
</script>
@endsection