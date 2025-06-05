<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Upload - Test API Image</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            padding: 20px;
        }

        .debug-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .debug-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #232c5a;
            padding-bottom: 20px;
        }

        .debug-header h1 {
            color: #232c5a;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .debug-header p {
            color: #666;
            font-size: 16px;
        }

        .api-info {
            background: #f8f9ff;
            border: 2px solid #e8ecff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .api-info h3 {
            color: #232c5a;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .api-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .api-detail {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .api-detail label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .api-detail span {
            color: #666;
            font-family: monospace;
            background: #f0f0f0;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 13px;
        }

        .upload-section {
            margin-bottom: 30px;
        }

        .upload-section h3 {
            color: #232c5a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .upload-form {
            background: #f8f9ff;
            border: 2px dashed #232c5a;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .upload-form.dragover {
            background: #e8f4ff;
            border-color: #1a2147;
        }

        .file-input {
            display: none;
        }

        .upload-area {
            cursor: pointer;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            background: rgba(35, 44, 90, 0.05);
            border-radius: 8px;
        }

        .upload-icon {
            font-size: 48px;
            color: #232c5a;
            margin-bottom: 15px;
        }

        .upload-text {
            font-size: 18px;
            color: #333;
            margin-bottom: 8px;
        }

        .upload-hint {
            font-size: 14px;
            color: #666;
        }

        .selected-file {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e8;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            display: none;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .file-name {
            font-weight: 600;
            color: #2e7d32;
        }

        .file-size {
            color: #666;
            font-size: 14px;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            margin: 10px auto;
            display: block;
        }

        .upload-btn {
            background: #232c5a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px auto 0;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: #1a2147;
            transform: translateY(-2px);
        }

        .upload-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .results-section {
            margin-top: 30px;
        }

        .results-section h3 {
            color: #232c5a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            display: none;
        }

        .result-box.success {
            background: #e8f5e8;
            border-color: #c8e6c9;
        }

        .result-box.error {
            background: #fff5f5;
            border-color: #fed7d7;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .result-header.success {
            color: #2e7d32;
        }

        .result-header.error {
            color: #c53030;
        }

        .result-data {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-wrap;
            overflow-x: auto;
        }

        .uploaded-image {
            text-align: center;
            margin: 15px 0;
        }

        .uploaded-image img {
            max-width: 300px;
            max-height: 300px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }

        .clear-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .clear-btn:hover {
            background: #5a6268;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #232c5a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #232c5a;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            padding: 8px 15px;
            background: #f8f9ff;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #232c5a;
            color: white;
        }

        @media (max-width: 768px) {
            .debug-container {
                margin: 10px;
                padding: 20px;
            }

            .api-details {
                grid-template-columns: 1fr;
            }

            .upload-form {
                padding: 20px;
            }

            .upload-icon {
                font-size: 36px;
            }

            .upload-text {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="debug-container">
        <a href="index.php?route=home" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>

        <div class="debug-header">
            <h1><i class="fas fa-bug"></i> Debug Upload API</h1>
            <p>Test de l'upload d'images vers l'API</p>
        </div>

        <div class="api-info">
            <h3><i class="fas fa-info-circle"></i> Informations API</h3>
            <div class="api-details">
                <div class="api-detail">
                    <label>Endpoint:</label>
                    <span>POST /api/upload/image</span>
                </div>
                <div class="api-detail">
                    <label>URL complète:</label>
                    <span>https://api.avironcastrais.fr/upload/image</span>
                </div>
                <div class="api-detail">
                    <label>Content-Type:</label>
                    <span>multipart/form-data</span>
                </div>
                <div class="api-detail">
                    <label>Champ requis:</label>
                    <span>image (fichier binaire)</span>
                </div>
                <div class="api-detail">
                    <label>Types acceptés:</label>
                    <span>image/jpeg, image/png, image/gif, image/webp</span>
                </div>
                <div class="api-detail">
                    <label>Taille max:</label>
                    <span>10MB</span>
                </div>
            </div>
        </div>

        <div class="upload-section">
            <h3><i class="fas fa-cloud-upload-alt"></i> Test d'upload</h3>
            
            <form id="uploadForm" class="upload-form">
                <input type="file" id="fileInput" class="file-input" accept="image/*">
                <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">Cliquez pour sélectionner une image</div>
                    <div class="upload-hint">ou glissez-déposez un fichier ici</div>
                    <div class="upload-hint">Formats supportés: JPG, PNG, GIF, WEBP (max 10MB)</div>
                </div>

                <div id="selectedFile" class="selected-file">
                    <div class="file-info">
                        <i class="fas fa-file-image"></i>
                        <span class="file-name" id="fileName"></span>
                        <span class="file-size" id="fileSize"></span>
                        <span class="file-type" id="fileType"></span>
                    </div>
                    <img id="previewImage" class="preview-image" style="display: none;">
                    <div class="file-details" id="fileDetails"></div>
                    <button type="submit" class="upload-btn" id="uploadBtn">
                        <i class="fas fa-upload"></i> Uploader l'image
                    </button>
                    <button type="button" class="clear-btn" onclick="clearSelection()">
                        <i class="fas fa-times"></i> Effacer
                    </button>
                </div>
            </form>

            <div id="loading" class="loading">
                <div class="spinner"></div>
                <p>Upload en cours...</p>
            </div>
        </div>

        <div class="results-section">
            <h3><i class="fas fa-clipboard-list"></i> Résultats</h3>
            
            <div id="successResult" class="result-box success">
                <div class="result-header success">
                    <i class="fas fa-check-circle"></i> Upload réussi
                </div>
                <div id="successData" class="result-data"></div>
                <div id="uploadedImageContainer" class="uploaded-image"></div>
            </div>

            <div id="errorResult" class="result-box error">
                <div class="result-header error">
                    <i class="fas fa-exclamation-triangle"></i> Erreur d'upload
                </div>
                <div id="errorData" class="result-data"></div>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');
        const selectedFileDiv = document.getElementById('selectedFile');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const previewImage = document.getElementById('previewImage');
        const uploadBtn = document.getElementById('uploadBtn');
        const loading = document.getElementById('loading');
        const successResult = document.getElementById('successResult');
        const errorResult = document.getElementById('errorResult');
        const successData = document.getElementById('successData');
        const errorData = document.getElementById('errorData');
        const uploadedImageContainer = document.getElementById('uploadedImageContainer');

        let selectedFile = null;

        // Gestion de la sélection de fichier
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleFileSelection(file);
            }
        });

        // Gestion du drag & drop
        const uploadArea = document.querySelector('.upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadForm.classList.add('dragover');
        }

        function unhighlight(e) {
            uploadForm.classList.remove('dragover');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                handleFileSelection(files[0]);
            }
        }

        function handleFileSelection(file) {
            // Vérifier le type de fichier
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner un fichier image');
                return;
            }

            // Vérifier la taille (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert('Le fichier est trop volumineux (max 10MB)');
                return;
            }

            selectedFile = file;
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Afficher le type MIME
            const fileType = document.getElementById('fileType');
            fileType.textContent = `Type: ${file.type}`;
            fileType.style.display = 'block';
            fileType.style.color = file.type.startsWith('image/') ? '#2e7d32' : '#c53030';
            
            // Afficher des détails supplémentaires
            const fileDetails = document.getElementById('fileDetails');
            fileDetails.innerHTML = `
                <div style="margin-top: 10px; padding: 10px; background: #f5f5f5; border-radius: 5px; font-size: 12px;">
                    <strong>Détails du fichier:</strong><br>
                    Nom: ${file.name}<br>
                    Type MIME: ${file.type}<br>
                    Taille: ${formatFileSize(file.size)}<br>
                    Dernière modification: ${new Date(file.lastModified).toLocaleString()}<br>
                    Extension détectée: ${file.name.split('.').pop().toLowerCase()}
                </div>
            `;
            
            // Afficher la prévisualisation
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(file);
            
            selectedFileDiv.style.display = 'block';
            
            // Masquer les résultats précédents
            hideResults();
            
            // Log pour debug
            console.log('Fichier sélectionné:', {
                name: file.name,
                type: file.type,
                size: file.size,
                lastModified: new Date(file.lastModified).toISOString()
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function clearSelection() {
            selectedFile = null;
            fileInput.value = '';
            selectedFileDiv.style.display = 'none';
            hideResults();
        }

        function hideResults() {
            successResult.style.display = 'none';
            errorResult.style.display = 'none';
        }

        // Gestion de l'upload
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedFile) {
                alert('Veuillez sélectionner un fichier');
                return;
            }

            uploadFile();
        });

        async function uploadFile() {
            const saveButton = document.getElementById('uploadBtn');
            const originalText = saveButton.innerHTML;
            
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Upload en cours...';
            loading.style.display = 'block';
            hideResults();

            const formData = new FormData();
            formData.append('image', selectedFile);

            try {
                console.log('=== DÉBUT UPLOAD ===');
                console.log('Fichier:', selectedFile.name);
                console.log('Type:', selectedFile.type);
                console.log('Taille:', selectedFile.size);
                console.log('FormData créé avec succès');

                const response = await fetch('https://api.avironcastrais.fr/upload/image', {
                    method: 'POST',
                    body: formData
                });

                console.log('Réponse HTTP Status:', response.status);
                console.log('Réponse HTTP StatusText:', response.statusText);
                
                let result;
                const contentType = response.headers.get('content-type');
                console.log('Content-Type de la réponse:', contentType);
                
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    const textResult = await response.text();
                    console.log('Réponse texte brute:', textResult);
                    try {
                        result = JSON.parse(textResult);
                    } catch (e) {
                        result = { error: 'Réponse non-JSON', response: textResult };
                    }
                }
                
                console.log('Résultat parsé:', result);
                
                if (response.ok) {
                    displaySuccess(result);
                } else {
                    displayError({
                        status: response.status,
                        statusText: response.statusText,
                        response: result
                    });
                }

            } catch (error) {
                console.error('=== ERREUR UPLOAD ===');
                console.error('Type d\'erreur:', error.constructor.name);
                console.error('Message:', error.message);
                console.error('Stack:', error.stack);
                
                displayError({
                    error: 'Erreur de réseau ou CORS',
                    message: error.message,
                    details: 'Vérifiez que l\'API est accessible sur http://localhost:3000 et que CORS est configuré'
                });
            } finally {
                saveButton.disabled = false;
                saveButton.innerHTML = originalText;
                loading.style.display = 'none';
            }
        }

        function displaySuccess(result) {
            successData.textContent = JSON.stringify(result, null, 2);
            successResult.style.display = 'block';
            
            // Afficher l'image uploadée si l'URL est fournie
            if (result.url) {
                uploadedImageContainer.innerHTML = `
                    <h4>Image uploadée:</h4>
                    <img src="${result.url}" alt="Image uploadée" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <p style="display: none; color: #c53030;">Impossible de charger l'image depuis: ${result.url}</p>
                    <p><strong>URL:</strong> <a href="${result.url}" target="_blank">${result.url}</a></p>
                `;
            }

            console.log('Upload réussi:', result);
        }

        function displayError(error) {
            errorData.textContent = JSON.stringify(error, null, 2);
            errorResult.style.display = 'block';
            
            console.error('Erreur upload:', error);
        }

        // Afficher les informations de debug au chargement
        console.log('=== DEBUG UPLOAD PAGE ===');
        console.log('API Endpoint: https://api.avironcastrais.fr/upload/image');
        console.log('Expected Content-Type: multipart/form-data');
        console.log('Expected field name: image');
        console.log('========================');
    </script>
</body>
</html>
