<section class="as_padderTop100 as_padderBottom100" style="background: var(--cosmic-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="kundli-view-container" style="background: linear-gradient(135deg, rgba(45, 24, 16, 0.9) 0%, rgba(61, 32, 24, 0.8) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); box-shadow: 0 20px 40px rgba(255, 112, 16, 0.2);">
                    
                    <!-- Back Button -->
                    <div class="mb-4">
                        <a href="<?php echo base_url('dashboard'); ?>" class="back-btn" style="color: var(--secondary-color); text-decoration: none; font-size: 16px; display: inline-flex; align-items: center; padding: 10px 15px; background: rgba(255, 112, 16, 0.1); border-radius: 8px; border: 1px solid rgba(255, 112, 16, 0.3); transition: all 0.3s ease;">
                            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                            Back to Dashboard
                        </a>
                    </div>

                    <!-- Kundli Header -->
                    <div class="kundli-header text-center mb-5">
                        <h1 class="as_heading" style="color: var(--secondary-color); margin-bottom: 10px;">
                            <i class="fas fa-scroll" style="margin-right: 15px; color: var(--secondary-color);"></i>
                            Kundli Details
                        </h1>
                        <h2 style="color: var(--primary-color); font-size: 24px; margin-bottom: 20px;">
                            <?php echo $kundli->name; ?>
                        </h2>
                    </div>

                    <!-- Kundli Information Grid -->
                    <div class="row mb-5">
                        <div class="col-md-6 mb-4">
                            <div class="info-card" style="background: linear-gradient(135deg, rgba(255, 112, 16, 0.1) 0%, rgba(255, 143, 0, 0.1) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 15px; padding: 25px;">
                                <h4 style="color: var(--secondary-color); margin-bottom: 20px;">
                                    <i class="fas fa-user" style="margin-right: 10px;"></i>
                                    Personal Information
                                </h4>
                                <div class="info-row" style="margin-bottom: 15px;">
                                    <span style="color: var(--secondary-color); font-weight: 600; display: inline-block; width: 100px;">Name:</span>
                                    <span style="color: var(--primary-color);"><?php echo $kundli->name; ?></span>
                                </div>
                                <?php if($kundli->whatsapp): ?>
                                <div class="info-row" style="margin-bottom: 15px;">
                                    <span style="color: var(--secondary-color); font-weight: 600; display: inline-block; width: 100px;">WhatsApp:</span>
                                    <span style="color: var(--primary-color);"><?php echo $kundli->whatsapp; ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="info-row">
                                    <span style="color: var(--secondary-color); font-weight: 600; display: inline-block; width: 100px;">Created:</span>
                                    <span style="color: var(--primary-color);"><?php echo date('M d, Y H:i', strtotime($kundli->created_at)); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-card" style="background: linear-gradient(135deg, rgba(255, 112, 16, 0.1) 0%, rgba(255, 143, 0, 0.1) 100%); border: 1px solid rgba(255, 112, 16, 0.3); border-radius: 15px; padding: 25px;">
                                <h4 style="color: var(--secondary-color); margin-bottom: 20px;">
                                    <i class="fas fa-calendar-alt" style="margin-right: 10px;"></i>
                                    Birth Information
                                </h4>
                                <div class="info-row" style="margin-bottom: 15px;">
                                    <span style="color: var(--secondary-color); font-weight: 600; display: inline-block; width: 100px;">Date:</span>
                                    <span style="color: var(--primary-color);"><?php echo date('F d, Y', strtotime($kundli->birth_date)); ?></span>
                                </div>
                                <div class="info-row" style="margin-bottom: 15px;">
                                    <span style="color: var(--secondary-color); font-weight: 600; display: inline-block; width: 100px;">Time:</span>
                                    <span style="color: var(--primary-color);"><?php echo $kundli->birth_time; ?></span>
                                </div>
                                <div class="info-row">
                                    <span style="color: var(--secondary-color); font-weight: 600; display: inline-block; width: 100px;">Place:</span>
                                    <span style="color: var(--primary-color);"><?php echo $kundli->birth_place; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kundli Data -->
                    <?php if($kundli->kundli_data): ?>
                        <?php $kundli_details = json_decode($kundli->kundli_data, true); ?>
                        <div class="kundli-data-section mb-5">
                            <h3 style="color: var(--secondary-color); margin-bottom: 30px; text-align: center;">
                                <i class="fas fa-star-of-david" style="margin-right: 10px;"></i>
                                Astrological Details
                            </h3>
                            
                            <div class="row">
                                <?php if(isset($kundli_details['gender'])): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item" style="background: rgba(255, 112, 16, 0.05); border: 1px solid rgba(255, 112, 16, 0.2); border-radius: 10px; padding: 20px;">
                                        <strong style="color: var(--secondary-color);">Gender:</strong>
                                        <span style="color: var(--primary-color); margin-left: 10px;"><?php echo ucfirst($kundli_details['gender']); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(isset($kundli_details['language'])): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item" style="background: rgba(255, 112, 16, 0.05); border: 1px solid rgba(255, 112, 16, 0.2); border-radius: 10px; padding: 20px;">
                                        <strong style="color: var(--secondary-color);">Language:</strong>
                                        <span style="color: var(--primary-color); margin-left: 10px;"><?php echo ucfirst($kundli_details['language']); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(isset($kundli_details['kundli_type'])): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item" style="background: rgba(255, 112, 16, 0.05); border: 1px solid rgba(255, 112, 16, 0.2); border-radius: 10px; padding: 20px;">
                                        <strong style="color: var(--secondary-color);">Kundli Type:</strong>
                                        <span style="color: var(--primary-color); margin-left: 10px;"><?php echo ucfirst($kundli_details['kundli_type']); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(isset($kundli_details['lat']) && isset($kundli_details['long'])): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item" style="background: rgba(255, 112, 16, 0.05); border: 1px solid rgba(255, 112, 16, 0.2); border-radius: 10px; padding: 20px;">
                                        <strong style="color: var(--secondary-color);">Coordinates:</strong>
                                        <span style="color: var(--primary-color); margin-left: 10px;"><?php echo $kundli_details['lat']; ?>, <?php echo $kundli_details['long']; ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="action-buttons text-center">
                        <?php 
                        $kundli_data = json_decode($kundli->kundli_data, true);
                        if ($kundli->local_pdf_path && file_exists(FCPATH . $kundli->local_pdf_path)): ?>
                            <a href="<?php echo base_url('dashboard/download_pdf/'.$kundli->id); ?>" target="_blank" class="as_btn" style="margin-right: 15px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <i class="fas fa-file-pdf" style="margin-right: 8px;"></i>
                                View PDF (Local)
                            </a>
                        <?php elseif (isset($kundli_data['pdf_url'])): ?>
                            <a href="<?php echo $kundli_data['pdf_url']; ?>" target="_blank" class="as_btn" style="margin-right: 15px; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                                <i class="fas fa-file-pdf" style="margin-right: 8px;"></i>
                                View PDF (External)
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo base_url('generate-kundli'); ?>" class="as_btn" style="margin-right: 15px;">
                            <i class="fas fa-plus-circle" style="margin-right: 8px;"></i>
                            Generate New Kundli
                        </a>
                        <a href="<?php echo base_url('dashboard'); ?>" class="as_btn" style="background: rgba(255, 112, 16, 0.1); color: var(--secondary-color); border: 2px solid var(--secondary-color);">
                            <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.back-btn:hover {
    background: rgba(255, 112, 16, 0.2) !important;
    border-color: var(--secondary-color) !important;
    transform: translateX(-3px);
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(255, 112, 16, 0.2);
}

.detail-item:hover {
    background: rgba(255, 112, 16, 0.1) !important;
    transform: scale(1.02);
}

.action-buttons .as_btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 112, 16, 0.4);
}
</style>