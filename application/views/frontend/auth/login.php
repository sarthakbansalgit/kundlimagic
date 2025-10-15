<?php $this->load->view('frontend/template/navbar')?>

<section class="as_padderTop100 as_padderBottom100" style="background: var(--cosmic-gradient);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="as_auth_box" style="background: linear-gradient(135deg, rgba(45, 24, 16, 0.9) 0%, rgba(61, 32, 24, 0.8) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); box-shadow: 0 20px 40px rgba(255, 112, 16, 0.2);">
                    <div class="text-center mb-4">
                        <h1 class="as_heading" style="color: var(--secondary-color); margin-bottom: 10px;">
                            <i class="fas fa-star-of-david" style="margin-right: 10px;"></i>
                            Login to Your Account
                        </h1>
                        <p style="color: var(--primary-color);">Access your personalized Kundli dashboard</p>
                    </div>

                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #ff6b6b; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo form_open('auth/login', array('class' => 'as_auth_form', 'method' => 'post')); ?>
                        <div class="form-group mb-4">
                            <label style="color: var(--primary-color); margin-bottom: 8px; font-weight: 600;">
                                <i class="fas fa-envelope" style="margin-right: 8px; color: var(--secondary-color);"></i>
                                Email Address
                            </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" value="<?php echo set_value('email'); ?>" required style="background: linear-gradient(135deg, #fff4e6 0%, #ffffff 100%) !important; border: 2px solid var(--accent-color) !important; border-radius: 10px; padding: 15px; font-size: 16px; color: #1a0d0a !important;">
                            <?php echo form_error('email', '<small class="text-danger"><i class="fas fa-exclamation-circle"></i> ', '</small>'); ?>
                        </div>

                        <div class="form-group mb-4">
                            <label style="color: var(--primary-color); margin-bottom: 8px; font-weight: 600;">
                                <i class="fas fa-lock" style="margin-right: 8px; color: var(--secondary-color);"></i>
                                Password
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required style="background: linear-gradient(135deg, #fff4e6 0%, #ffffff 100%) !important; border: 2px solid var(--accent-color) !important; border-radius: 10px; padding: 15px; font-size: 16px; color: #1a0d0a !important;">
                            <?php echo form_error('password', '<small class="text-danger"><i class="fas fa-exclamation-circle"></i> ', '</small>'); ?>
                        </div>

                        <div class="text-center mb-4">
                            <button type="submit" class="as_btn glow-orange" style="width: 100%; font-size: 16px; padding: 15px; border-radius: 10px; margin-left: 0; background: linear-gradient(135deg, #ff7010 0%, #ff8f00 50%, #e65100 100%) !important; color: #ffffff !important; border: 2px solid #ffa726 !important;">
                                <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                                Login to Dashboard
                            </button>
                        </div>

                        <div class="text-center">
                            <p style="color: var(--primary-color);">
                                Don't have an account? 
                                <a href="<?php echo base_url('auth/register'); ?>" style="color: var(--secondary-color); font-weight: 600; text-decoration: none;">
                                    <i class="fas fa-user-plus"></i> Register Here
                                </a>
                            </p>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.as_auth_box {
    position: relative;
    overflow: hidden;
}

.as_auth_box::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 112, 16, 0.1) 0%, transparent 50%);
    animation: cosmic-rotate 20s linear infinite;
    z-index: -1;
}

@keyframes cosmic-rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.as_auth_form .form-control:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 20px rgba(255, 112, 16, 0.3);
    transform: translateY(-2px);
}
</style>