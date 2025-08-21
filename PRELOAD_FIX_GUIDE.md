# Data Preload Functionality Fix - Testing Guide

## Overview
This document describes the fixes applied to resolve the automatic data preload issue for event registration by RFC and phone number in both company and guest flows.

## Problem Statement
The automatic data preloading was not working for:
- RFC search (example: RARD7909214H6) in company registration
- Phone search (example: 4424865389) in guest registration

Data existed in the database but preloading was failing.

## Root Causes Identified

### 1. Duplicate Event Listeners
- Both global app.js and form-specific scripts were adding event listeners
- This could cause conflicts and multiple API calls

### 2. Insufficient Error Handling
- Limited debugging information when API calls failed
- No validation before triggering API calls
- Poor error feedback to users

### 3. Inflexible Database Queries
- RFC search was case-sensitive
- Phone search required exact format match
- No handling for common data variations

### 4. Missing Input Validation
- API calls triggered without proper validation
- Could cause unnecessary server requests

## Fixes Applied

### JavaScript Improvements (assets/js/app.js)

#### Duplicate Listener Prevention
```javascript
// Added protection against duplicate listeners
if (rfcInput && !rfcInput.hasAttribute('data-search-attached')) {
    rfcInput.setAttribute('data-search-attached', 'true');
    // Add listener only once
}
```

#### Enhanced Error Handling
```javascript
.then(response => {
    console.log('Response status:', response.status);
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
})
```

#### Improved Logging
```javascript
console.log('Searching for RFC:', rfc, 'Event slug:', eventSlug);
console.log('API URL:', apiUrl);
console.log('API response:', data);
```

### Backend Improvements (app/controllers/ApiController.php)

#### Case-Insensitive RFC Search
```php
// Old: "SELECT * FROM empresas WHERE rfc = ?"
// New: "SELECT * FROM empresas WHERE UPPER(rfc) = UPPER(?)"
```

#### Flexible Phone Search
```php
// Search both exact match and cleaned numbers
$telefonoClean = preg_replace('/\D/', '', $telefono);
$query = "SELECT * FROM invitados WHERE telefono = ? OR REPLACE(REPLACE(REPLACE(telefono, '-', ''), ' ', ''), '(', '') = ?";
```

#### Exception Handling
```php
try {
    // Database operations
} catch (Exception $e) {
    error_log("Error en buscarEmpresa: " . $e->getMessage());
    $this->json(['error' => 'Error interno del servidor'], 500);
}
```

### Form Cleanup

#### Removed Duplicate Listeners
- Removed form-specific event listeners for RFC/phone search
- Kept only form-specific validation logic
- Added documentation comments

## Testing

### Test Data
Created `test_data.sql` with the specific examples:
- RFC: RARD7909214H6 (Tecnología Avanzada RARD S.A. de C.V.)
- Phone: 4424865389 (María Fernanda González Torres)

### Test Page
Created `test_preload.html` for isolated testing with:
- Console logging display
- Manual test fields
- Step-by-step testing instructions

### Integration Test
Created automated test script to verify:
- Code modifications
- File consistency
- Configuration correctness
- JavaScript syntax validation

## Manual Testing Steps

### 1. Database Setup
```sql
-- Run the schema first
mysql -u username -p database_name < database/schema.sql

-- Then add test data
mysql -u username -p database_name < test_data.sql
```

### 2. Server Configuration
- Ensure mod_rewrite is enabled
- Configure BASE_URL correctly in config/config.php
- Verify database credentials

### 3. Testing RFC Preload
1. Navigate to a company registration page
2. Enter RFC: `RARD7909214H6`
3. Wait 500ms after typing
4. Verify:
   - Loading indicator appears
   - API call is made (check network tab)
   - Form fields populate automatically
   - Success message appears

### 4. Testing Phone Preload
1. Navigate to a guest registration page
2. Enter phone: `4424865389`
3. Wait 500ms after typing
4. Verify:
   - Loading indicator appears
   - API call is made (check network tab)
   - Form fields populate automatically
   - Success message appears

### 5. Console Debugging
Check browser console for:
```
Searching for RFC: RARD7909214H6 Event slug: event-name
API URL: /reservaciones/api/buscar-empresa
Response status: 200
API response: {found: true, empresa: {...}, representante: {...}}
```

## Troubleshooting

### Common Issues

#### 1. No API Call Triggered
- Check if event listener is attached (console should show "Event listeners attached")
- Verify input has correct `data-event-slug` attribute
- Ensure input length meets minimum requirements (12 for RFC, 10 for phone)

#### 2. API Returns 404
- Verify BASE_URL configuration in config.php
- Check .htaccess rewrite rules
- Ensure ApiController.php exists and is accessible

#### 3. API Returns 500
- Check PHP error logs
- Verify database connection
- Ensure test data exists in database

#### 4. Data Not Populating
- Check API response in browser network tab
- Verify field IDs match between response and form
- Check for JavaScript errors in console

#### 5. Multiple API Calls
- Should be fixed with duplicate listener prevention
- If still occurring, check for remaining duplicate listeners

### Debug Mode
The enhanced logging provides detailed information:
- API call triggers
- Response status codes
- Full response data
- Error details

## Expected Behavior

### Successful RFC Search
1. User types RFC (12+ characters)
2. 500ms delay
3. Loading indicator shows
4. API call to `/api/buscar-empresa`
5. If found: Form fields populate + success message
6. If not found: Info message about continuing registration

### Successful Phone Search
1. User types phone (10+ digits)
2. 500ms delay
3. Loading indicator shows
4. API call to `/api/buscar-invitado`
5. If found: Form fields populate + success message
6. If not found: Info message about continuing registration

## Performance Considerations

### Debouncing
- 500ms delay prevents excessive API calls
- Only triggers after user stops typing

### Validation
- Client-side validation before API calls
- Prevents unnecessary server requests

### Error Recovery
- Graceful degradation on API failures
- User can continue registration manually

## Security Improvements

### Input Sanitization
- Trimmed input data
- Validated input formats
- Protected against injection

### Error Handling
- Safe error messages to users
- Detailed logging for developers
- No sensitive data exposure

## Future Enhancements

### Possible Improvements
1. Caching of frequently searched data
2. Fuzzy search for similar RFCs/phones
3. Real-time validation indicators
4. Progressive data loading
5. Offline mode support

### Monitoring
Consider adding:
- API usage metrics
- Search success rates
- Performance monitoring
- Error tracking

## Conclusion

The data preload functionality has been comprehensively fixed with:
- Improved reliability through better error handling
- Enhanced user experience with better feedback
- Increased flexibility in data matching
- Comprehensive testing and debugging tools

The solution maintains backward compatibility while providing a more robust and user-friendly experience.