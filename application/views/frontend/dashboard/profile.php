<style>
    :root {
        --primary-color: #ff7010;
        --secondary-color: #ffa500;
        --dark-bg: #1a0f0a;
        --card-bg: rgba(45, 24, 16, 0.8);
    }

    .profile-container {
        min-height: 80vh;
        background: linear-gradient(135deg, 
            #2d1810 0%, 
            #3d2018 25%, 
            #4a2820 50%, 
            #3d2018 75%, 
            #2d1810 100%);
        position: relative;
        overflow: hidden;
    }

    .profile-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 70%, rgba(255, 112, 16, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 70% 30%, rgba(255, 165, 0, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .profile-card {
        background: linear-gradient(135deg, rgba(45, 24, 16, 0.9) 0%, rgba(61, 32, 24, 0.7) 100%);
        border: 1px solid rgba(255, 112, 16, 0.3);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 
                    0 0 20px rgba(255, 112, 16, 0.1);
        position: relative;
        z-index: 1;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 30px;
        border-radius: 20px 20px 0 0;
        text-align: center;
        position: relative;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="stars" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23stars)"/></svg>');
        border-radius: 20px 20px 0 0;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 36px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 1;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        color: var(--secondary-color);
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 112, 16, 0.3);
        border-radius: 10px;
        color: var(--primary-color);
        padding: 12px 15px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
        color: var(--primary-color);
        outline: none;
    }

    .form-control::placeholder {
        color: rgba(255, 112, 16, 0.5);
    }

    .btn-update {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 112, 16, 0.3);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 112, 16, 0.4);
        color: white;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 112, 16, 0.3);
        color: var(--primary-color);
        padding: 10px 25px;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: rgba(255, 112, 16, 0.1);
        color: var(--secondary-color);
        text-decoration: none;
        transform: translateX(-3px);
    }

    .alert {
        border: none;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .profile-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 112, 16, 0.2);
    }

    .stat-item {
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: white;
        display: block;
    }

    .stat-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 5px;
    }

    .floating-particles {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--secondary-color);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        opacity: 0.3;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); }
        33% { transform: translateY(-20px) translateX(10px) rotate(120deg); }
        66% { transform: translateY(10px) translateX(-10px) rotate(240deg); }
    }

    .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
    .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
    .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
    .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
    .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
    .particle:nth-child(7) { left: 70%; animation-delay: 0.5s; }
    .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; }
    .particle:nth-child(9) { left: 90%; animation-delay: 2.5s; }
</style>

<div class="profile-container">
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>

                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2 style="margin: 0; position: relative; z-index: 1;">Edit Profile</h2>
                        <p style="margin: 10px 0 0; opacity: 0.9; position: relative; z-index: 1;">
                            Update your personal information
                        </p>
                        
                        <div class="profile-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?php echo count($this->User_model->get_user_kundlis($this->session->userdata('user_id'))); ?></span>
                                <div class="stat-label">Kundlis</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo date('M Y', strtotime($user->created_at)); ?></span>
                                <div class="stat-label">Member Since</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">Active</span>
                                <div class="stat-label">Status</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo current_url(); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user me-2"></i>Full Name
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="name" 
                                               value="<?php echo set_value('name', $user->name); ?>" 
                                               placeholder="Enter your full name"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               value="<?php echo $user->email; ?>" 
                                               placeholder="Your email address"
                                               readonly
                                               style="background: rgba(255, 255, 255, 0.02); cursor: not-allowed;">
                                        <small style="color: rgba(255, 112, 16, 0.6); font-size: 12px; margin-top: 5px; display: block;">
                                            <i class="fas fa-lock me-1"></i>Email cannot be changed
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-map-marker-alt me-2"></i>Place of Birth
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="pob" 
                                               value="<?php echo set_value('pob', isset($user->pob) ? $user->pob : ''); ?>" 
                                               placeholder="Enter your place of birth (city, country)"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-calendar-alt me-2"></i>Member Since
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?php echo date('F d, Y', strtotime($user->created_at)); ?>" 
                                               readonly
                                               style="background: rgba(255, 255, 255, 0.02); cursor: not-allowed;">
                                    </div>
                                </div>
                            </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-group mb-0">
                                            <small style="color: rgba(255, 112, 16, 0.7);">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Your information is secure and will only be used for kundli generation
                                            </small>
                                        </div>
                                        <button type="submit" class="btn-update">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="mt-5 pt-4" style="border-top: 1px solid rgba(255, 112, 16, 0.2);">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div style="color: var(--secondary-color); margin-bottom: 10px;">
                                        <i class="fas fa-star" style="font-size: 24px;"></i>
                                    </div>
                                    <h6 style="color: var(--primary-color); margin: 0;">Personalized</h6>
                                    <small style="color: rgba(255, 112, 16, 0.7);">Custom kundli reports</small>
                                </div>
                                <div class="col-md-4">
                                    <div style="color: var(--secondary-color); margin-bottom: 10px;">
                                        <i class="fas fa-lock" style="font-size: 24px;"></i>
                                    </div>
                                    <h6 style="color: var(--primary-color); margin: 0;">Secure</h6>
                                    <small style="color: rgba(255, 112, 16, 0.7);">Data protection</small>
                                </div>
                                <div class="col-md-4">
                                    <div style="color: var(--secondary-color); margin-bottom: 10px;">
                                        <i class="fas fa-clock" style="font-size: 24px;"></i>
                                    </div>
                                    <h6 style="color: var(--primary-color); margin: 0;">Instant</h6>
                                    <small style="color: rgba(255, 112, 16, 0.7);">Quick generation</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add floating animation to particles
    const particles = document.querySelectorAll('.particle');
    particles.forEach((particle, index) => {
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = (index * 0.5) + 's';
    });

    // Form validation enhancement
    const form = document.querySelector('form');
    const nameInput = document.querySelector('input[name="name"]');

    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate name
            if (nameInput && nameInput.value.trim().length < 2) {
                isValid = false;
                nameInput.style.borderColor = '#ef4444';
            } else if (nameInput) {
                nameInput.style.borderColor = 'rgba(255, 112, 16, 0.3)';
            }
            
            if (!isValid) {
                e.preventDefault();
                // Show error animation
                const button = form.querySelector('.btn-update');
                if (button) {
                    button.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        button.style.animation = '';
                    }, 500);
                }
            }
        });
    }

    // Add input focus effects
    const inputs = document.querySelectorAll('.form-control:not([readonly])');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'all 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
});

// Add shake animation for validation errors
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>