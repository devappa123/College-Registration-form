// ========================================
// Global Variables
// ========================================
let currentStep = 1;
const totalSteps = 6;
let formData = {};

// ========================================
// Document Ready
// ========================================
$(document).ready(function() {
    // Load saved form data from localStorage
    loadFormData();
    
    // Initialize
    updateProgress();
    
    // Event Listeners
    setupEventListeners();
});

// ========================================
// Setup Event Listeners
// ========================================
function setupEventListeners() {
    // Theme Toggle
    $('.theme-toggle').click(toggleTheme);
    
    // Navigation Buttons
    $('#nextBtn').click(handleNext);
    $('#prevBtn').click(handlePrevious);
    
    // Form Submission
    $('#admissionForm').submit(handleSubmit);
    
    // Entrance Exam Radio Buttons
    $('input[name="examType"]').change(handleExamTypeChange);
    
    // File Upload Display
    $('input[type="file"]').change(handleFileSelect);
    
    // Declaration Checkbox
    $('#declarationCheck').change(handleDeclarationChange);
    
    // Download Preview Button
    $('#downloadPreviewBtn').click(downloadPreview);
    
    // Save form data on input change
    $('#admissionForm').on('change input', 'input, textarea, select', saveFormData);
}

// ========================================
// Theme Toggle
// ========================================
function toggleTheme() {
    $('body').toggleClass('dark-mode');
    const icon = $('#themeIcon');
    
    if ($('body').hasClass('dark-mode')) {
        icon.removeClass('fa-moon').addClass('fa-sun');
        localStorage.setItem('theme', 'dark');
    } else {
        icon.removeClass('fa-sun').addClass('fa-moon');
        localStorage.setItem('theme', 'light');
    }
}

// Load theme preference
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    $('body').addClass('dark-mode');
    $('#themeIcon').removeClass('fa-moon').addClass('fa-sun');
}

// ========================================
// Form Navigation
// ========================================
function handleNext() {
    if (validateStep(currentStep)) {
        if (currentStep === 4) {
            // Before moving to preview, generate preview content
            generatePreview();
        }
        
        currentStep++;
        showStep(currentStep);
        updateProgress();
    }
}

function handlePrevious() {
    currentStep--;
    showStep(currentStep);
    updateProgress();
}

function showStep(step) {
    $('.form-step').removeClass('active');
    $(`.form-step[data-step="${step}"]`).addClass('active');
    
    // Update navigation buttons
    if (step === 1) {
        $('#prevBtn').hide();
    } else {
        $('#prevBtn').show();
    }
    
    if (step === totalSteps) {
        $('#nextBtn').hide();
    } else {
        $('#nextBtn').show();
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    $('#progressFill').css('width', progress + '%');
    $('#progressText').text(`Step ${currentStep} of ${totalSteps}`);
}

// ========================================
// Form Validation
// ========================================
function validateStep(step) {
    let isValid = true;
    const $currentStep = $(`.form-step[data-step="${step}"]`);
    
    // Clear previous errors
    $currentStep.find('.form-group').removeClass('error');
    $currentStep.find('.error-message').text('');
    
    switch(step) {
        case 1:
            isValid = validatePersonalInfo($currentStep);
            break;
        case 2:
            isValid = validateAcademicInfo($currentStep);
            break;
        case 3:
            isValid = validateEntranceExam($currentStep);
            break;
        case 4:
            isValid = validateDocuments($currentStep);
            break;
        case 5:
            // Preview step - no validation needed
            break;
        case 6:
            isValid = validateDeclaration($currentStep);
            break;
    }
    
    return isValid;
}

function validatePersonalInfo($step) {
    let isValid = true;
    
    // Full Name
    const fullName = $('#fullName').val().trim();
    if (!fullName) {
        showError($('#fullName'), 'Full name is required');
        isValid = false;
    }
    
    // Date of Birth
    const dob = $('#dob').val();
    if (!dob) {
        showError($('#dob'), 'Date of birth is required');
        isValid = false;
    }
    
    // Gender
    if (!$('input[name="gender"]:checked').val()) {
        showError($('input[name="gender"]').first(), 'Please select gender');
        isValid = false;
    }
    
    // Mobile Number
    const mobile = $('#mobile').val().trim();
    if (!mobile) {
        showError($('#mobile'), 'Mobile number is required');
        isValid = false;
    } else if (!/^\d{10}$/.test(mobile)) {
        showError($('#mobile'), 'Mobile number must be exactly 10 digits');
        isValid = false;
    }
    
    // Email
    const email = $('#email').val().trim();
    if (!email) {
        showError($('#email'), 'Email is required');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError($('#email'), 'Please enter a valid email address');
        isValid = false;
    }
    
    // Address
    const address = $('#address').val().trim();
    if (!address) {
        showError($('#address'), 'Address is required');
        isValid = false;
    }
    
    // Parent Name
    const parentName = $('#parentName').val().trim();
    if (!parentName) {
        showError($('#parentName'), 'Parent/Guardian name is required');
        isValid = false;
    }
    
    // Parent Phone
    const parentPhone = $('#parentPhone').val().trim();
    if (!parentPhone) {
        showError($('#parentPhone'), 'Parent/Guardian phone is required');
        isValid = false;
    } else if (!/^\d{10}$/.test(parentPhone)) {
        showError($('#parentPhone'), 'Phone number must be exactly 10 digits');
        isValid = false;
    }
    
    return isValid;
}

function validateAcademicInfo($step) {
    let isValid = true;
    
    // 10th Standard
    const school10 = $('#school10').val().trim();
    if (!school10) {
        showError($('#school10'), 'School name is required');
        isValid = false;
    }
    
    const board10 = $('#board10').val().trim();
    if (!board10) {
        showError($('#board10'), 'Board is required');
        isValid = false;
    }
    
    const year10 = $('#year10').val();
    if (!year10) {
        showError($('#year10'), 'Year is required');
        isValid = false;
    }
    
    const percentage10 = $('#percentage10').val();
    if (!percentage10) {
        showError($('#percentage10'), 'Percentage is required');
        isValid = false;
    } else if (percentage10 < 0 || percentage10 > 100) {
        showError($('#percentage10'), 'Percentage must be between 0 and 100');
        isValid = false;
    }
    
    // 12th Standard
    const college12 = $('#college12').val().trim();
    if (!college12) {
        showError($('#college12'), 'College name is required');
        isValid = false;
    }
    
    const board12 = $('#board12').val().trim();
    if (!board12) {
        showError($('#board12'), 'Board is required');
        isValid = false;
    }
    
    const year12 = $('#year12').val();
    if (!year12) {
        showError($('#year12'), 'Year is required');
        isValid = false;
    }
    
    const percentage12 = $('#percentage12').val();
    if (!percentage12) {
        showError($('#percentage12'), 'Percentage is required');
        isValid = false;
    } else if (percentage12 < 0 || percentage12 > 100) {
        showError($('#percentage12'), 'Percentage must be between 0 and 100');
        isValid = false;
    }
    
    return isValid;
}

function validateEntranceExam($step) {
    let isValid = true;
    
    const examType = $('input[name="examType"]:checked').val();
    if (!examType) {
        showError($('input[name="examType"]').first(), 'Please select an entrance exam');
        isValid = false;
    } else {
        if (examType === 'KCET') {
            const kcetRank = $('#kcetRank').val();
            if (!kcetRank || kcetRank < 1) {
                showError($('#kcetRank'), 'Please enter a valid KCET rank');
                isValid = false;
            }
        } else if (examType === 'COMEDK') {
            const comedkRank = $('#comedkRank').val();
            if (!comedkRank || comedkRank < 1) {
                showError($('#comedkRank'), 'Please enter a valid COMEDK rank');
                isValid = false;
            }
        }
    }
    
    return isValid;
}

function validateDocuments($step) {
    let isValid = true;
    
    const requiredFiles = ['photoUpload', 'markcard10', 'markcard12', 'tcUpload'];
    
    requiredFiles.forEach(function(fileId) {
        const fileInput = $(`#${fileId}`)[0];
        if (!fileInput.files || fileInput.files.length === 0) {
            showError($(`#${fileId}`), 'This file is required');
            isValid = false;
        } else {
            const file = fileInput.files[0];
            
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                showError($(`#${fileId}`), 'File size must be less than 2MB');
                isValid = false;
            }
            
            // Validate file type
            const allowedTypes = {
                'photoUpload': ['image/jpeg', 'image/jpg', 'image/png'],
                'markcard10': ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'],
                'markcard12': ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'],
                'tcUpload': ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']
            };
            
            if (!allowedTypes[fileId].includes(file.type)) {
                showError($(`#${fileId}`), 'Invalid file type. Please upload jpg, png, or pdf');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function validateDeclaration($step) {
    let isValid = true;
    
    if (!$('#declarationCheck').is(':checked')) {
        showError($('#declarationCheck'), 'You must accept the declaration to proceed');
        isValid = false;
    }
    
    return isValid;
}

function showError($element, message) {
    $element.closest('.form-group').addClass('error');
    $element.closest('.form-group').find('.error-message').text(message);
}

// ========================================
// Entrance Exam Handler
// ========================================
function handleExamTypeChange() {
    const examType = $(this).val();
    
    // Hide both rank fields
    $('#kcetRankGroup, #comedkRankGroup').hide();
    $('#kcetRank, #comedkRank').prop('required', false);
    
    // Show the selected rank field
    if (examType === 'KCET') {
        $('#kcetRankGroup').show();
        $('#kcetRank').prop('required', true);
    } else if (examType === 'COMEDK') {
        $('#comedkRankGroup').show();
        $('#comedkRank').prop('required', true);
    }
}

// ========================================
// File Upload Handler
// ========================================
function handleFileSelect() {
    const file = this.files[0];
    const $wrapper = $(this).closest('.file-upload-wrapper');
    
    if (file) {
        $wrapper.find('.file-name').text(file.name);
    } else {
        $wrapper.find('.file-name').text('');
    }
}

// ========================================
// Declaration Handler
// ========================================
function handleDeclarationChange() {
    if ($(this).is(':checked')) {
        $('#finalSubmitBtn').prop('disabled', false);
    } else {
        $('#finalSubmitBtn').prop('disabled', true);
    }
}

// ========================================
// Generate Preview
// ========================================
function generatePreview() {
    const formData = getFormData();
    
    let previewHTML = `
        <div class="preview-section">
            <h3>Personal Information</h3>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Full Name</div>
                    <div class="preview-value">${formData.fullName}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Date of Birth</div>
                    <div class="preview-value">${formData.dob}</div>
                </div>
            </div>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Gender</div>
                    <div class="preview-value">${formData.gender}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Mobile Number</div>
                    <div class="preview-value">${formData.mobile}</div>
                </div>
            </div>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Email</div>
                    <div class="preview-value">${formData.email}</div>
                </div>
            </div>
            <div class="preview-item">
                <div class="preview-label">Address</div>
                <div class="preview-value">${formData.address}</div>
            </div>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Parent/Guardian Name</div>
                    <div class="preview-value">${formData.parentName}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Parent/Guardian Phone</div>
                    <div class="preview-value">${formData.parentPhone}</div>
                </div>
            </div>
        </div>

        <div class="preview-section">
            <h3>Academic Information</h3>
            <h4 style="margin: 1rem 0 0.5rem; color: var(--text-secondary);">10th Standard</h4>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">School Name</div>
                    <div class="preview-value">${formData.school10}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Board</div>
                    <div class="preview-value">${formData.board10}</div>
                </div>
            </div>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Year of Passing</div>
                    <div class="preview-value">${formData.year10}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Percentage</div>
                    <div class="preview-value">${formData.percentage10}%</div>
                </div>
            </div>

            <h4 style="margin: 1rem 0 0.5rem; color: var(--text-secondary);">12th Standard</h4>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">College Name</div>
                    <div class="preview-value">${formData.college12}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Board</div>
                    <div class="preview-value">${formData.board12}</div>
                </div>
            </div>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Year of Passing</div>
                    <div class="preview-value">${formData.year12}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Percentage</div>
                    <div class="preview-value">${formData.percentage12}%</div>
                </div>
            </div>
        </div>

        <div class="preview-section">
            <h3>Entrance Exam Information</h3>
            <div class="preview-row">
                <div class="preview-item">
                    <div class="preview-label">Exam Type</div>
                    <div class="preview-value">${formData.examType}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Rank</div>
                    <div class="preview-value">${formData.rank}</div>
                </div>
            </div>
        </div>

        <div class="preview-section">
            <h3>Documents Uploaded</h3>
            <div class="preview-item">
                <div class="preview-label">✓ Student Photo</div>
            </div>
            <div class="preview-item">
                <div class="preview-label">✓ 10th Mark Card</div>
            </div>
            <div class="preview-item">
                <div class="preview-label">✓ 12th Mark Card</div>
            </div>
            <div class="preview-item">
                <div class="preview-label">✓ Transfer Certificate</div>
            </div>
        </div>
    `;
    
    $('#previewContent').html(previewHTML);
}

// ========================================
// Download Preview PDF
// ========================================
function downloadPreview() {
    const element = document.getElementById('previewContent');
    const opt = {
        margin: 1,
        filename: 'Admission_Preview.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(element).save();
}

// ========================================
// Get Form Data
// ========================================
function getFormData() {
    const examType = $('input[name="examType"]:checked').val();
    let rank = '';
    
    if (examType === 'KCET') {
        rank = $('#kcetRank').val();
    } else if (examType === 'COMEDK') {
        rank = $('#comedkRank').val();
    }
    
    return {
        fullName: $('#fullName').val(),
        dob: $('#dob').val(),
        gender: $('input[name="gender"]:checked').val(),
        mobile: $('#mobile').val(),
        email: $('#email').val(),
        address: $('#address').val(),
        parentName: $('#parentName').val(),
        parentPhone: $('#parentPhone').val(),
        school10: $('#school10').val(),
        board10: $('#board10').val(),
        year10: $('#year10').val(),
        percentage10: $('#percentage10').val(),
        college12: $('#college12').val(),
        board12: $('#board12').val(),
        year12: $('#year12').val(),
        percentage12: $('#percentage12').val(),
        examType: examType,
        rank: rank
    };
}

// ========================================
// LocalStorage Functions
// ========================================
function saveFormData() {
    const formData = getFormData();
    localStorage.setItem('admissionFormData', JSON.stringify(formData));
}

function loadFormData() {
    const savedData = localStorage.getItem('admissionFormData');
    
    if (savedData) {
        try {
            const formData = JSON.parse(savedData);
            
            // Populate form fields
            $('#fullName').val(formData.fullName || '');
            $('#dob').val(formData.dob || '');
            $(`input[name="gender"][value="${formData.gender}"]`).prop('checked', true);
            $('#mobile').val(formData.mobile || '');
            $('#email').val(formData.email || '');
            $('#address').val(formData.address || '');
            $('#parentName').val(formData.parentName || '');
            $('#parentPhone').val(formData.parentPhone || '');
            $('#school10').val(formData.school10 || '');
            $('#board10').val(formData.board10 || '');
            $('#year10').val(formData.year10 || '');
            $('#percentage10').val(formData.percentage10 || '');
            $('#college12').val(formData.college12 || '');
            $('#board12').val(formData.board12 || '');
            $('#year12').val(formData.year12 || '');
            $('#percentage12').val(formData.percentage12 || '');
            
            if (formData.examType) {
                $(`input[name="examType"][value="${formData.examType}"]`).prop('checked', true).trigger('change');
                
                if (formData.examType === 'KCET') {
                    $('#kcetRank').val(formData.rank || '');
                } else if (formData.examType === 'COMEDK') {
                    $('#comedkRank').val(formData.rank || '');
                }
            }
        } catch (e) {
            console.error('Error loading form data:', e);
        }
    }
}

function clearFormData() {
    localStorage.removeItem('admissionFormData');
}

// ========================================
// Form Submission
// ========================================
function handleSubmit(e) {
    e.preventDefault();
    
    // Show loading overlay
    $('#loadingOverlay').addClass('active');
    
    // Create FormData object
    const formDataObj = new FormData();
    
    // Add text fields
    const textData = getFormData();
    for (let key in textData) {
        formDataObj.append(key, textData[key]);
    }
    
    // Add files
    formDataObj.append('photoUpload', $('#photoUpload')[0].files[0]);
    formDataObj.append('markcard10', $('#markcard10')[0].files[0]);
    formDataObj.append('markcard12', $('#markcard12')[0].files[0]);
    formDataObj.append('tcUpload', $('#tcUpload')[0].files[0]);
    
    // Submit to PHP backend
    $.ajax({
        url: 'submit.php',
        type: 'POST',
        data: formDataObj,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#loadingOverlay').removeClass('active');
            
            // Log raw response for debugging
            console.log('Raw response:', response);
            console.log('Response type:', typeof response);
            
            try {
                // If response is already an object, use it directly
                const result = typeof response === 'string' ? JSON.parse(response) : response;
                
                console.log('Parsed result:', result);
                
                if (result.success) {
                    // Clear localStorage
                    clearFormData();
                    
                    // Redirect to success page
                    window.location.href = `success.php?id=${result.data.applicationId}`;
                } else {
                    alert('Error: ' + (result.message || 'Submission failed'));
                    if (result.debug) {
                        console.error('Debug info:', result.debug);
                    }
                }
            } catch (e) {
                console.error('Parse error:', e);
                console.error('Response was:', response);
                alert('Error processing response: ' + e.message + '\n\nCheck browser console (F12) for details.');
            }
        },
        error: function(xhr, status, error) {
            $('#loadingOverlay').removeClass('active');
            alert('Error submitting form: ' + error);
        }
    });
}
