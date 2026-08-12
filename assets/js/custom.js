document.addEventListener('DOMContentLoaded', function() {
    initColorPicker();
    initSizePicker();
    initFileUpload();
    initCustomForm();
});
// Color Picker
function initColorPicker() {
    const colorOptions = document.querySelectorAll('.color-option');
    const colorInput = document.querySelector('input[name="color"]');

    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            if (colorInput) {
                colorInput.value = this.dataset.color;
            }
        });
    });
}

// Size Picker
function initSizePicker() {
    const sizeOptions = document.querySelectorAll('.size-option');
    const sizeInput = document.querySelector('input[name="size"]');

    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            sizeOptions.forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            if (sizeInput) {
                sizeInput.value = this.dataset.size;
            }
        });
    });
}

// File Upload
function initFileUpload() {
    const uploadArea = document.querySelector('.file-upload-area');
    const fileInput = document.querySelector('input[name="reference_image"]');
    const fileNameDisplay = document.querySelector('.file-name');

    if (!uploadArea || !fileInput) return;

    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });

    function handleFileSelect(file) {
        if (fileNameDisplay) {
            fileNameDisplay.textContent = file.name;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('error', 'Please upload a valid image file (JPG, PNG, GIF, WebP)');
            fileInput.value = '';
            if (fileNameDisplay) fileNameDisplay.textContent = '';
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('error', 'File size must be less than 5MB');
            fileInput.value = '';
            if (fileNameDisplay) fileNameDisplay.textContent = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = uploadArea.querySelector('img');
            if (!preview) {
                preview = document.createElement('img');
                preview.style.maxWidth = '200px';
                preview.style.maxHeight = '200px';
                preview.style.borderRadius = '8px';
                preview.style.marginTop = '15px';
                uploadArea.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Custom Form Validation
function initCustomForm() {
    const form = document.querySelector('.custom-designer-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        // Validate color selection
        const colorInput = form.querySelector('input[name="color"]');
        if (!colorInput.value) {
            e.preventDefault();
            showNotification('error', 'Please select a color');
            return false;
        }

        // Validate size selection
        const sizeInput = form.querySelector('input[name="size"]');
        if (!sizeInput.value) {
            e.preventDefault();
            showNotification('error', 'Please select a size');
            return false;
        }

        // Validate budget
        const budgetInput = form.querySelector('input[name="budget"]');
        const budget = parseFloat(budgetInput.value);
        if (!budget || budget <= 0) {
            e.preventDefault();
            showNotification('error', 'Please enter a valid budget');
            return false;
        }

        return true;
    });
}

// Budget slider (if implemented)
function initBudgetSlider() {
    const slider = document.querySelector('.budget-slider');
    const display = document.querySelector('.budget-display');
    const input = document.querySelector('input[name="budget"]');

    if (!slider) return;

    slider.addEventListener('input', function() {
        const value = this.value;
        if (display) display.textContent = '₹' + value;
        if (input) input.value = value;
    });
}
