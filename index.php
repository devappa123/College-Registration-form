<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Registration Portal</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <!-- Dark Mode Toggle -->
    <div class="theme-toggle">
        <i class="fas fa-moon" id="themeIcon"></i>
    </div>

    <div class="container">
        <header class="header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <h1>Engineering College Admission Portal</h1>
            </div>
            <p class="subtitle">Academic Year 2024-25</p>
        </header>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <p class="progress-text" id="progressText">Step 1 of 6</p>
        </div>

        <!-- Form Container -->
        <div class="form-card">
            <form id="admissionForm" enctype="multipart/form-data">
                
                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <h2 class="step-title"><i class="fas fa-user"></i> Personal Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Full Name <span class="required">*</span></label>
                            <input type="text" id="fullName" name="fullName" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="dob">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="dob" name="dob" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Gender <span class="required">*</span></label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="Male" required>
                                    <span>Male</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="Female">
                                    <span>Female</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="Other">
                                    <span>Other</span>
                                </label>
                            </div>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile">Mobile Number <span class="required">*</span></label>
                            <input type="tel" id="mobile" name="mobile" placeholder="10 digits" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Permanent Address <span class="required">*</span></label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="parentName">Parent/Guardian Name <span class="required">*</span></label>
                            <input type="text" id="parentName" name="parentName" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="parentPhone">Parent/Guardian Phone <span class="required">*</span></label>
                            <input type="tel" id="parentPhone" name="parentPhone" placeholder="10 digits" required>
                            <span class="error-message"></span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Academic Information -->
                <div class="form-step" data-step="2">
                    <h2 class="step-title"><i class="fas fa-book"></i> Academic Information</h2>
                    
                    <h3 class="subsection-title">10th Standard Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="school10">School Name <span class="required">*</span></label>
                            <input type="text" id="school10" name="school10" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="board10">Board <span class="required">*</span></label>
                            <input type="text" id="board10" name="board10" placeholder="e.g., CBSE, ICSE, State" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="year10">Year of Passing <span class="required">*</span></label>
                            <input type="number" id="year10" name="year10" min="2000" max="2024" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="percentage10">Percentage <span class="required">*</span></label>
                            <input type="number" id="percentage10" name="percentage10" min="0" max="100" step="0.01" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <h3 class="subsection-title">12th Standard Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="college12">College Name <span class="required">*</span></label>
                            <input type="text" id="college12" name="college12" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="board12">Board <span class="required">*</span></label>
                            <input type="text" id="board12" name="board12" placeholder="e.g., PUC, CBSE" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="year12">Year of Passing <span class="required">*</span></label>
                            <input type="number" id="year12" name="year12" min="2000" max="2024" required>
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="percentage12">Percentage <span class="required">*</span></label>
                            <input type="number" id="percentage12" name="percentage12" min="0" max="100" step="0.01" required>
                            <span class="error-message"></span>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Entrance Exam Information -->
                <div class="form-step" data-step="3">
                    <h2 class="step-title"><i class="fas fa-clipboard-check"></i> Entrance Exam Information</h2>
                    
                    <div class="form-group">
                        <label>Select Entrance Exam <span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="examType" value="KCET" required>
                                <span>KCET</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="examType" value="COMEDK">
                                <span>COMEDK</span>
                            </label>
                        </div>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group" id="kcetRankGroup" style="display: none;">
                        <label for="kcetRank">KCET Rank <span class="required">*</span></label>
                        <input type="number" id="kcetRank" name="kcetRank" min="1">
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group" id="comedkRankGroup" style="display: none;">
                        <label for="comedkRank">COMEDK Rank <span class="required">*</span></label>
                        <input type="number" id="comedkRank" name="comedkRank" min="1">
                        <span class="error-message"></span>
                    </div>
                </div>

                <!-- Step 4: Document Uploads -->
                <div class="form-step" data-step="4">
                    <h2 class="step-title"><i class="fas fa-file-upload"></i> Document Uploads</h2>
                    <p class="upload-note"><i class="fas fa-info-circle"></i> Maximum file size: 2MB. Allowed formats: .jpg, .png, .pdf</p>
                    
                    <div class="form-group">
                        <label for="photoUpload">Student Photo <span class="required">*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="photoUpload" name="photoUpload" accept=".jpg,.jpeg,.png" required>
                            <label for="photoUpload" class="file-upload-label">
                                <i class="fas fa-camera"></i>
                                <span>Choose Photo</span>
                            </label>
                            <span class="file-name"></span>
                        </div>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="markcard10">10th Mark Card <span class="required">*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="markcard10" name="markcard10" accept=".jpg,.jpeg,.png,.pdf" required>
                            <label for="markcard10" class="file-upload-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose File</span>
                            </label>
                            <span class="file-name"></span>
                        </div>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="markcard12">12th Mark Card <span class="required">*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="markcard12" name="markcard12" accept=".jpg,.jpeg,.png,.pdf" required>
                            <label for="markcard12" class="file-upload-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose File</span>
                            </label>
                            <span class="file-name"></span>
                        </div>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="tcUpload">Transfer Certificate (TC) <span class="required">*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="tcUpload" name="tcUpload" accept=".jpg,.jpeg,.png,.pdf" required>
                            <label for="tcUpload" class="file-upload-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose File</span>
                            </label>
                            <span class="file-name"></span>
                        </div>
                        <span class="error-message"></span>
                    </div>
                </div>

                <!-- Step 5: Preview -->
                <div class="form-step" data-step="5">
                    <h2 class="step-title"><i class="fas fa-eye"></i> Preview Application</h2>
                    <p class="preview-note">Please review your details carefully before submission</p>
                    
                    <div class="preview-actions">
                        <button type="button" class="btn btn-secondary" id="downloadPreviewBtn">
                            <i class="fas fa-download"></i> Download Preview PDF
                        </button>
                    </div>

                    <div id="previewContent" class="preview-content">
                        <!-- Preview content will be generated here -->
                    </div>
                </div>

                <!-- Step 6: Declaration & Submit -->
                <div class="form-step" data-step="6">
                    <h2 class="step-title"><i class="fas fa-check-circle"></i> Declaration & Submit</h2>
                    
                    <div class="declaration-box">
                        <h3>Declaration</h3>
                        <p>I hereby declare that all the information provided in this application form is true, accurate, and complete to the best of my knowledge. I understand that any false or misleading information may result in the rejection of my application or cancellation of admission.</p>
                        
                        <label class="checkbox-label">
                            <input type="checkbox" id="declarationCheck" name="declaration" required>
                            <span>I confirm that the information provided is true and submitted by me.</span>
                        </label>
                        <span class="error-message"></span>
                    </div>

                    <div class="final-submit-section">
                        <button type="submit" class="btn btn-success btn-submit" id="finalSubmitBtn" disabled>
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <footer class="footer">
            <p>&copy; 2024 Engineering College. All rights reserved.</p>
        </footer>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <p>Submitting your application...</p>
    </div>

    <!-- Script -->
    <script src="assets/script.js"></script>
</body>
</html>
