/* Base64 encoded small placeholder image */
const canvas = document.createElement('canvas');
canvas.width = 50;
canvas.height = 50;
const ctx = canvas.getContext('2d');

// Background
ctx.fillStyle = '#cccccc';
ctx.fillRect(0, 0, 50, 50);

// Team text
ctx.fillStyle = '#666666';
ctx.font = 'bold 12px Arial';
ctx.textAlign = 'center';
ctx.textBaseline = 'middle';
ctx.fillText('Équipe', 25, 25);

// Save to data URL
const dataUrl = canvas.toDataURL('image/png');
console.log(dataUrl);
