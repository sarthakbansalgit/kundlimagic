<section class="as_padderTop100 as_padderBottom100" style="background: var(--cosmic-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-container" style="background: linear-gradient(135deg, rgba(45, 24, 16, 0.9) 0%, rgba(61, 32, 24, 0.8) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); box-shadow: 0 20px 40px rgba(255, 112, 16, 0.2);">
                    
                    <!-- Dashboard Header -->
                    <div class="dashboard-header text-center mb-5">
                        <h1 class="as_heading" style="color: #ffffff !important; margin-bottom: 10px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                            <i class="fas fa-tachometer-alt" style="margin-right: 15px; color: #ffa500 !important;"></i>
                            Cosmic Dashboard
                        </h1>
                        <p style="color: #ffffff !important; font-size: 18px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                            Welcome, <strong style="color: #ffa500 !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><?php echo $user->name; ?></strong>! 
                            Explore your astrological journey
                        </p>
                    </div>

                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Dashboard Stats -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="stat-card" style="background: linear-gradient(135deg, rgba(255, 112, 16, 0.1) 0%, rgba(255, 143, 0, 0.1) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;">
                                <i class="fas fa-scroll" style="font-size: 36px; color: #ffa500 !important; margin-bottom: 15px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                                <h3 style="color: #ffffff !important; margin-bottom: 5px; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><?php echo count($kundlis); ?></h3>
                                <p style="color: #ffffff !important; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Total Kundlis</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card" style="background: linear-gradient(135deg, rgba(255, 112, 16, 0.1) 0%, rgba(255, 143, 0, 0.1) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;">
                                <i class="fas fa-calendar-alt" style="font-size: 36px; color: #ffa500 !important; margin-bottom: 15px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                                <h3 style="color: #ffffff !important; margin-bottom: 5px; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><?php echo date('M Y', strtotime($user->created_at)); ?></h3>
                                <p style="color: #ffffff !important; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Member Since</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <a href="<?php echo base_url('generate-kundli'); ?>" class="action-card" style="display: block; background: linear-gradient(135deg, #ff7010 0%, #ffa500 100%); border-radius: 15px; padding: 25px; text-decoration: none; color: #ffffff !important; text-align: center; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(255, 112, 16, 0.3); text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                <i class="fas fa-plus-circle" style="font-size: 36px; margin-bottom: 15px; color: #ffffff !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                                <h4 style="margin-bottom: 10px; color: #ffffff !important; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Generate New Kundli</h4>
                                <p style="margin: 0; opacity: 1; color: #ffffff !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Create a new personalized birth chart</p>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo base_url('dashboard/profile'); ?>" class="action-card" style="display: block; background: linear-gradient(135deg, rgba(45, 24, 16, 0.8) 0%, rgba(61, 32, 24, 0.6) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 15px; padding: 25px; text-decoration: none; color: #ffffff !important; text-align: center; transition: all 0.3s ease;">
                                <i class="fas fa-user-edit" style="font-size: 36px; color: #ffa500 !important; margin-bottom: 15px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                                <h4 style="margin-bottom: 10px; color: #ffffff !important; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Edit Profile</h4>
                                <p style="margin: 0; opacity: 1; color: #ffffff !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Update your personal information</p>
                            </a>
                        </div>
                    </div>

                    <!-- Kundlis List -->
                    <div class="kundlis-section">
                        <h2 class="as_heading" style="color: #ffffff !important; margin-bottom: 30px; text-align: center; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                            <i class="fas fa-star-of-david" style="margin-right: 10px; color: #ffa500 !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                            Your Kundlis
                        </h2>

                        <?php if(count($kundlis) > 0): ?>
                            <div class="row">
                                <?php foreach($kundlis as $kundli): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="kundli-card" style="background: linear-gradient(135deg, rgba(45, 24, 16, 0.6) 0%, rgba(61, 32, 24, 0.4) 100%); border: 1px solid rgba(255, 112, 16, 0.2); border-radius: 15px; padding: 25px; transition: all 0.3s ease;">
                                            <div class="kundli-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                                <h4 style="color: #ffffff !important; margin: 0; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <i class="fas fa-scroll" style="margin-right: 8px; color: #ffa500 !important;"></i>
                                                    <?php echo $kundli->name; ?>
                                                </h4>
                                                <span class="kundli-date" style="color: #ffffff !important; font-size: 12px; opacity: 1; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <?php echo date('M d, Y', strtotime($kundli->created_at)); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="kundli-details" style="margin-bottom: 20px;">
                                                <p style="color: #ffffff !important; margin: 5px 0; font-size: 14px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <i class="fas fa-calendar" style="color: #ffa500 !important; margin-right: 8px;"></i>
                                                    <strong>Birth Date:</strong> <?php echo date('M d, Y', strtotime($kundli->birth_date)); ?>
                                                </p>
                                                <p style="color: #ffffff !important; margin: 5px 0; font-size: 14px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <i class="fas fa-clock" style="color: #ffa500 !important; margin-right: 8px;"></i>
                                                    <strong>Birth Time:</strong> <?php echo $kundli->birth_time; ?>
                                                </p>
                                                <p style="color: #ffffff !important; margin: 5px 0; font-size: 14px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <i class="fas fa-map-marker-alt" style="color: #ffa500 !important; margin-right: 8px;"></i>
                                                    <strong>Birth Place:</strong> <?php echo $kundli->birth_place; ?>
                                                </p>
                                                <?php if($kundli->whatsapp): ?>
                                                <p style="color: #ffffff !important; margin: 5px 0; font-size: 14px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <i class="fab fa-whatsapp" style="color: #ffa500 !important; margin-right: 8px;"></i>
                                                    <strong>WhatsApp:</strong> <?php echo $kundli->whatsapp; ?>
                                                </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="<?php echo base_url('dashboard/kundli/'.$kundli->id); ?>" class="as_btn" style="flex: 1; text-align: center; margin-right: 10px; border-radius: 8px; background: linear-gradient(135deg, #ff7010 0%, #ffa500 100%); color: #ffffff !important; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                    <i class="fas fa-eye" style="margin-right: 8px; color: #ffffff !important;"></i>
                                                    View Details
                                                </a>
                                                
                                                <?php 
                                                $kundli_data = json_decode($kundli->kundli_data, true);
                                                if ($kundli->local_pdf_path && file_exists(FCPATH . $kundli->local_pdf_path)): ?>
                                                    <a href="<?php echo base_url('dashboard/download_pdf/'.$kundli->id); ?>" target="_blank" class="as_btn" style="flex: 1; text-align: center; border-radius: 8px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff !important; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                        <i class="fas fa-file-pdf" style="margin-right: 8px; color: #ffffff !important;"></i>
                                                        PDF
                                                    </a>
                                                <?php elseif (isset($kundli_data['pdf_url'])): ?>
                                                    <a href="<?php echo $kundli_data['pdf_url']; ?>" target="_blank" class="as_btn" style="flex: 1; text-align: center; border-radius: 8px; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: #ffffff !important; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                                        <i class="fas fa-file-pdf" style="margin-right: 8px; color: #ffffff !important;"></i>
                                                        PDF (Ext)
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state" style="text-align: center; padding: 60px 20px;">
                                <i class="fas fa-star-of-david" style="font-size: 72px; color: #ffa500 !important; opacity: 0.5; margin-bottom: 30px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                                <h3 style="color: #ffffff !important; margin-bottom: 15px; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">No Kundlis Yet</h3>
                                <p style="color: #ffffff !important; margin-bottom: 30px; opacity: 1; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                    Start your astrological journey by generating your first Kundli
                                </p>
                                <a href="<?php echo base_url('generate-kundli'); ?>" class="as_btn glow-orange" style="background: linear-gradient(135deg, #ff7010 0%, #ffa500 100%); color: #ffffff !important; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); border: none; box-shadow: 0 8px 25px rgba(255, 112, 16, 0.4);">
                                    <i class="fas fa-plus-circle" style="margin-right: 8px; color: #ffffff !important;"></i>
                                    Generate Your First Kundli
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.dashboard-container {
    position: relative;
    overflow: hidden;
}

.dashboard-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 112, 16, 0.1) 0%, transparent 50%);
    animation: cosmic-rotate 30s linear infinite;
    z-index: -1;
}

@keyframes cosmic-rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.stat-card:hover,
.kundli-card:hover {
    transform: translateY(-5px);
    border-color: var(--secondary-color);
    box-shadow: 0 15px 30px rgba(255, 112, 16, 0.2);
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(255, 112, 16, 0.4);
}

.kundli-card {
    position: relative;
}

.kundli-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(255, 112, 16, 0.05), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 15px;
}

.kundli-card:hover::before {
    opacity: 1;
}

.action-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 15px 35px rgba(255, 112, 16, 0.4) !important;
}

.stat-card:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 10px 25px rgba(255, 112, 16, 0.3) !important;
}

.kundli-card:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 10px 25px rgba(255, 112, 16, 0.3) !important;
}

/* Ensure all text remains bright white on hover */
.action-card:hover h4,
.action-card:hover p,
.action-card:hover i {
    color: #ffffff !important;
}

.stat-card:hover h3,
.stat-card:hover p {
    color: #ffffff !important;
}

.stat-card:hover i {
    color: #ffa500 !important;
}
</style>