#!/bin/bash

# Final validation script for data preload fix
# This script performs comprehensive validation of the implemented solution

echo "==============================================="
echo "  CANACO DATA PRELOAD FIX - FINAL VALIDATION"
echo "==============================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

passed=0
failed=0
warnings=0

check_pass() {
    echo -e "  ${GREEN}✓${NC} $1"
    ((passed++))
}

check_fail() {
    echo -e "  ${RED}✗${NC} $1"
    ((failed++))
}

check_warn() {
    echo -e "  ${YELLOW}⚠${NC} $1"
    ((warnings++))
}

echo -e "${BLUE}1. VERIFYING FILE MODIFICATIONS${NC}"
echo "────────────────────────────────"

# Check app.js modifications
if [ -f "assets/js/app.js" ]; then
    check_pass "app.js exists"
    
    if grep -q "data-search-attached" assets/js/app.js; then
        check_pass "Duplicate listener prevention implemented"
    else
        check_fail "Duplicate listener prevention missing"
    fi
    
    if grep -q "console.log.*Searching for RFC" assets/js/app.js; then
        check_pass "RFC search debugging added"
    else
        check_fail "RFC search debugging missing"
    fi
    
    if grep -q "console.log.*Searching for phone" assets/js/app.js; then
        check_pass "Phone search debugging added"
    else
        check_fail "Phone search debugging missing"
    fi
    
    if grep -q "if (!response.ok)" assets/js/app.js; then
        check_pass "Enhanced error handling in fetch"
    else
        check_fail "Enhanced error handling missing"
    fi
else
    check_fail "app.js not found"
fi

echo ""

# Check ApiController.php modifications
if [ -f "app/controllers/ApiController.php" ]; then
    check_pass "ApiController.php exists"
    
    if grep -q "UPPER(rfc) = UPPER" app/controllers/ApiController.php; then
        check_pass "Case-insensitive RFC search implemented"
    else
        check_fail "Case-insensitive RFC search missing"
    fi
    
    if grep -q "preg_replace.*telefono" app/controllers/ApiController.php; then
        check_pass "Flexible phone search implemented"
    else
        check_fail "Flexible phone search missing"
    fi
    
    if grep -q "error_log.*Error en buscar" app/controllers/ApiController.php; then
        check_pass "Error logging implemented"
    else
        check_fail "Error logging missing"
    fi
    
    if grep -q "trim.*input\[" app/controllers/ApiController.php; then
        check_pass "Input trimming implemented"
    else
        check_fail "Input trimming missing"
    fi
else
    check_fail "ApiController.php not found"
fi

echo ""

echo -e "${BLUE}2. VERIFYING REGISTRATION FORMS${NC}"
echo "─────────────────────────────────"

# Check company registration form
if [ -f "app/views/public/company-registration.php" ]; then
    check_pass "Company registration form exists"
    
    if grep -q "Note: RFC search is handled globally" app/views/public/company-registration.php; then
        check_pass "Global RFC handling documented"
    else
        check_fail "Global RFC handling not documented"
    fi
    
    if ! grep -q "CANACO.registration.searchByRFC.*eventSlug" app/views/public/company-registration.php; then
        check_pass "Duplicate RFC listener removed"
    else
        check_fail "Duplicate RFC listener still present"
    fi
else
    check_fail "Company registration form not found"
fi

# Check guest registration form
if [ -f "app/views/public/guest-registration.php" ]; then
    check_pass "Guest registration form exists"
    
    if grep -q "Note: Phone search is handled globally" app/views/public/guest-registration.php; then
        check_pass "Global phone handling documented"
    else
        check_fail "Global phone handling not documented"
    fi
    
    if ! grep -q "CANACO.registration.searchByPhone.*eventSlug" app/views/public/guest-registration.php; then
        check_pass "Duplicate phone listener removed"
    else
        check_fail "Duplicate phone listener still present"
    fi
else
    check_fail "Guest registration form not found"
fi

echo ""

echo -e "${BLUE}3. VERIFYING TEST MATERIALS${NC}"
echo "─────────────────────────────"

# Check test data
if [ -f "test_data.sql" ]; then
    check_pass "Test data SQL file created"
    
    if grep -q "RARD7909214H6" test_data.sql; then
        check_pass "Problem statement RFC included"
    else
        check_fail "Problem statement RFC missing"
    fi
    
    if grep -q "4424865389" test_data.sql; then
        check_pass "Problem statement phone included"
    else
        check_fail "Problem statement phone missing"
    fi
    
    if grep -q "ON DUPLICATE KEY UPDATE" test_data.sql; then
        check_pass "Safe insert statements used"
    else
        check_warn "No duplicate key handling (may cause errors on re-run)"
    fi
else
    check_fail "Test data SQL file not found"
fi

# Check test page
if [ -f "test_preload.html" ]; then
    check_pass "Standalone test page created"
    
    if grep -q "Test Data Preload Functionality" test_preload.html; then
        check_pass "Test page properly titled"
    else
        check_fail "Test page title missing"
    fi
    
    if grep -q "Console Log" test_preload.html; then
        check_pass "Console logging interface included"
    else
        check_fail "Console logging interface missing"
    fi
else
    check_fail "Standalone test page not found"
fi

# Check API test script
if [ -f "test_api_endpoints.sh" ]; then
    check_pass "API test script created"
    
    if [ -x "test_api_endpoints.sh" ]; then
        check_pass "API test script is executable"
    else
        check_warn "API test script not executable"
    fi
else
    check_fail "API test script not found"
fi

echo ""

echo -e "${BLUE}4. VERIFYING CONFIGURATION${NC}"
echo "────────────────────────────"

# Check configuration files
if [ -f "config/config.php" ]; then
    check_pass "Configuration file exists"
    
    if grep -q "define('BASE_URL'" config/config.php; then
        BASE_URL=$(grep "define('BASE_URL'" config/config.php | sed "s/.*define('BASE_URL', '\(.*\)');.*/\1/")
        check_pass "BASE_URL configured: $BASE_URL"
    else
        check_fail "BASE_URL not configured"
    fi
else
    check_fail "Configuration file not found"
fi

# Check header meta tag
if [ -f "app/views/layouts/header.php" ]; then
    check_pass "Header layout exists"
    
    if grep -q 'meta name="base-url"' app/views/layouts/header.php; then
        check_pass "Base URL meta tag present"
    else
        check_fail "Base URL meta tag missing"
    fi
else
    check_fail "Header layout not found"
fi

echo ""

echo -e "${BLUE}5. SYNTAX AND STRUCTURE VALIDATION${NC}"
echo "────────────────────────────────────"

# Check JavaScript syntax if Node.js is available
if command -v node >/dev/null 2>&1; then
    if node -c assets/js/app.js 2>/dev/null; then
        check_pass "JavaScript syntax valid"
    else
        check_fail "JavaScript syntax errors found"
    fi
else
    check_warn "Node.js not available for JavaScript syntax checking"
fi

# Check PHP syntax if available
if command -v php >/dev/null 2>&1; then
    if php -l app/controllers/ApiController.php >/dev/null 2>&1; then
        check_pass "PHP syntax valid"
    else
        check_fail "PHP syntax errors found"
    fi
else
    check_warn "PHP not available for syntax checking"
fi

echo ""

echo -e "${BLUE}6. DOCUMENTATION VERIFICATION${NC}"
echo "─────────────────────────────"

if [ -f "PRELOAD_FIX_GUIDE.md" ]; then
    check_pass "Comprehensive documentation created"
    
    if grep -q "Root Causes Identified" PRELOAD_FIX_GUIDE.md; then
        check_pass "Root cause analysis documented"
    else
        check_fail "Root cause analysis missing"
    fi
    
    if grep -q "Manual Testing Steps" PRELOAD_FIX_GUIDE.md; then
        check_pass "Testing instructions provided"
    else
        check_fail "Testing instructions missing"
    fi
    
    if grep -q "Troubleshooting" PRELOAD_FIX_GUIDE.md; then
        check_pass "Troubleshooting guide included"
    else
        check_fail "Troubleshooting guide missing"
    fi
else
    check_fail "Documentation file not found"
fi

echo ""

echo "==============================================="
echo -e "${BLUE}VALIDATION SUMMARY${NC}"
echo "==============================================="
echo ""
echo -e "Tests passed:    ${GREEN}$passed${NC}"
echo -e "Tests failed:    ${RED}$failed${NC}"
echo -e "Warnings:        ${YELLOW}$warnings${NC}"
echo ""

if [ $failed -eq 0 ]; then
    echo -e "${GREEN}✓ All critical tests passed!${NC}"
    echo ""
    echo "NEXT STEPS:"
    echo "1. Set up database with schema.sql"
    echo "2. Run test_data.sql to add test data"
    echo "3. Configure web server"
    echo "4. Test with RFC: RARD7909214H6"
    echo "5. Test with phone: 4424865389"
    echo ""
    echo "For detailed testing: ./test_api_endpoints.sh"
    echo "For documentation: cat PRELOAD_FIX_GUIDE.md"
    echo ""
    exit 0
else
    echo -e "${RED}✗ $failed critical issues found!${NC}"
    echo ""
    echo "Please review the failed checks above."
    exit 1
fi