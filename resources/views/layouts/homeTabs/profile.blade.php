<section id="profile" class="page-section">
    <div class="profile-card">
        <div class="avatar-container">
            <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="avatar-img" id="profile-avatar">
            <div class="avatar-upload-overlay" id="avatar-upload-overlay">
                <i class="fas fa-camera"></i>
                <span>Change Avatar</span>
            </div>
            <input type="file" id="avatar-input" accept="image/*" style="display: none;">
        </div>
        <div class="profile-info">
            <h1>{{ Auth::user()->fName }} {{ Auth::user()->lName }}</h1>
            <p>{{ Auth::user()->email }} <br> {{ Auth::user()->mobNumber }}</p>
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ Auth::user()->posts()->count() }}</span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ Auth::user()->followers_count }}</span>
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ Auth::user()->following_count }}</span>
                    <span class="stat-label">Following</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid" style="grid-template-columns: 1fr 1fr; margin-top: 24px;">
        <div class="widget-card">
            <h3>Basic Info</h3>
            <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-muted);">First Name</label>
                    <input type="text" id="firstName" value="{{ Auth::user()->fName }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                </div>
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-muted);">Last Name</label>
                    <input type="text" id="lastName" value="{{ Auth::user()->lName }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                </div>
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-muted);">Email Address</label>
                    <input type="email" id="email" value="{{ Auth::user()->email }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                </div>
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-muted);">Mobile Number</label>
                    <input type="text" id="mobile" value="{{ Auth::user()->mobNumber }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                </div>
                <button class="btn btn-primary" onclick="updateProfile()">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
        
        <div class="widget-card">
            <h3>Skills & Tags</h3>
            <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="skills-container">
                @php
                    $skills = ['UI Design', 'Figma', 'HTML/CSS', 'Prototyping', 'JavaScript', 'Laravel', 'PHP', 'MySQL'];
                @endphp
                @foreach($skills as $skill)
                    <span class="skill-tag">{{ $skill }}</span>
                @endforeach
            </div>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="new-skill" placeholder="Add new skill" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                <button class="btn btn-outline" onclick="addSkill()">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
    </div>
    
    <div class="widget-card" style="margin-top: 24px;">
        <h3>Account Security</h3>
        <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">Current Password</label>
                <input type="password" id="currentPassword" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
            </div>
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">New Password</label>
                <input type="password" id="newPassword" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
            </div>
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">Confirm New Password</label>
                <input type="password" id="confirmPassword" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
            </div>
            <button class="btn btn-primary" onclick="changePassword()">
                <i class="fas fa-key"></i> Change Password
            </button>
        </div>
    </div>
</section>

<style>
.avatar-container {
    position: relative;
    width: 120px;
    height: 120px;
    cursor: pointer;
}

.avatar-upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    border-radius: var(--radius-smart);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s;
    cursor: pointer;
}

.avatar-container:hover .avatar-upload-overlay {
    opacity: 1;
}

.avatar-upload-overlay i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.avatar-upload-overlay span {
    font-size: 0.8rem;
    font-weight: 500;
}

.skill-tag {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: all 0.2s;
}

.skill-tag:hover {
    background: rgba(67, 97, 238, 0.2);
}

.skill-tag .remove-skill {
    opacity: 0;
    font-size: 0.7rem;
    transition: opacity 0.2s;
}

.skill-tag:hover .remove-skill {
    opacity: 1;
}
</style>

<script>
// Avatar upload
document.getElementById('avatar-upload-overlay').addEventListener('click', function() {
    document.getElementById('avatar-input').click();
});

document.getElementById('avatar-input').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const file = e.target.files[0];
        const formData = new FormData();
        formData.append('avatar', file);
        
        fetch('/avatar/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profile-avatar').src = data.avatar_url + '?t=' + Date.now();
                showToast('Avatar updated successfully!', 'success');
            } else {
                showToast('Failed to upload avatar', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error uploading avatar', 'error');
        });
    }
});

// Update profile
function updateProfile() {
    const data = {
        fName: document.getElementById('firstName').value,
        lName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        mobNumber: document.getElementById('mobile').value
    };
    
    fetch('/profile/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Profile updated successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to update profile', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating profile', 'error');
    });
}

// Skills management
function addSkill() {
    const skillInput = document.getElementById('new-skill');
    const skill = skillInput.value.trim();
    
    if (skill) {
        const skillTag = document.createElement('span');
        skillTag.className = 'skill-tag';
        skillTag.innerHTML = `${skill} <span class="remove-skill" onclick="removeSkill(this)"><i class="fas fa-times"></i></span>`;
        
        document.getElementById('skills-container').appendChild(skillTag);
        skillInput.value = '';
        
        showToast('Skill added!', 'success');
    }
}

function removeSkill(element) {
    const skillTag = element.closest('.skill-tag');
    skillTag.remove();
    showToast('Skill removed!', 'normal');
}

// Change password
function changePassword() {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showToast('Please fill all password fields', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showToast('New passwords do not match', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showToast('Password must be at least 6 characters', 'error');
        return;
    }
    
    fetch('/profile/change-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            current_password: currentPassword,
            new_password: newPassword,
            new_password_confirmation: confirmPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Password changed successfully!', 'success');
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        } else {
            showToast(data.message || 'Failed to change password', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error changing password', 'error');
    });
}
</script>