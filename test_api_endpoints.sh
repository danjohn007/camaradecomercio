#!/bin/bash

# Quick API test script
# Tests the API endpoints directly using curl

BASE_URL="http://localhost/reservaciones"  # Adjust as needed

echo "=== API Endpoint Testing ==="
echo "Base URL: $BASE_URL"
echo ""

# Test 1: RFC Search API
echo "1. Testing RFC Search API..."
echo "POST $BASE_URL/api/buscar-empresa"

RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"rfc":"RARD7909214H6"}' \
    "$BASE_URL/api/buscar-empresa" 2>/dev/null)

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS:" | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_STATUS:/d')

echo "Status: $HTTP_STATUS"
if [ "$HTTP_STATUS" = "200" ]; then
    echo "✓ API accessible"
    echo "Response: $BODY"
    
    if echo "$BODY" | grep -q '"found":true'; then
        echo "✓ Test RFC found in database"
    else
        echo "ℹ Test RFC not found (may need to run test_data.sql)"
    fi
else
    echo "✗ API error (Status: $HTTP_STATUS)"
    echo "Response: $BODY"
fi

echo ""

# Test 2: Phone Search API
echo "2. Testing Phone Search API..."
echo "POST $BASE_URL/api/buscar-invitado"

RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"telefono":"4424865389"}' \
    "$BASE_URL/api/buscar-invitado" 2>/dev/null)

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS:" | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_STATUS:/d')

echo "Status: $HTTP_STATUS"
if [ "$HTTP_STATUS" = "200" ]; then
    echo "✓ API accessible"
    echo "Response: $BODY"
    
    if echo "$BODY" | grep -q '"found":true'; then
        echo "✓ Test phone found in database"
    else
        echo "ℹ Test phone not found (may need to run test_data.sql)"
    fi
else
    echo "✗ API error (Status: $HTTP_STATUS)"
    echo "Response: $BODY"
fi

echo ""

# Test 3: Alternative RFC (from schema.sql)
echo "3. Testing existing RFC from schema..."
echo "POST $BASE_URL/api/buscar-empresa"

RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"rfc":"ABC123456789"}' \
    "$BASE_URL/api/buscar-empresa" 2>/dev/null)

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS:" | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_STATUS:/d')

echo "Status: $HTTP_STATUS"
if [ "$HTTP_STATUS" = "200" ]; then
    echo "✓ API accessible"
    echo "Response: $BODY"
    
    if echo "$BODY" | grep -q '"found":true'; then
        echo "✓ Schema RFC found in database"
    else
        echo "ℹ Schema RFC not found"
    fi
else
    echo "✗ API error (Status: $HTTP_STATUS)"
    echo "Response: $BODY"
fi

echo ""

# Test 4: Alternative Phone (from schema.sql)
echo "4. Testing existing phone from schema..."
echo "POST $BASE_URL/api/buscar-invitado"

RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"telefono":"4421234567"}' \
    "$BASE_URL/api/buscar-invitado" 2>/dev/null)

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS:" | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_STATUS:/d')

echo "Status: $HTTP_STATUS"
if [ "$HTTP_STATUS" = "200" ]; then
    echo "✓ API accessible"
    echo "Response: $BODY"
    
    if echo "$BODY" | grep -q '"found":true'; then
        echo "✓ Schema phone found in database"
    else
        echo "ℹ Schema phone not found"
    fi
else
    echo "✗ API error (Status: $HTTP_STATUS)"
    echo "Response: $BODY"
fi

echo ""
echo "=== Test Summary ==="
echo ""
echo "If APIs return 404:"
echo "- Check BASE_URL configuration"
echo "- Verify .htaccess rules"
echo "- Ensure web server is running"
echo ""
echo "If APIs return 500:"
echo "- Check PHP error logs"
echo "- Verify database connection"
echo "- Run database schema setup"
echo ""
echo "If data not found:"
echo "- Run: mysql -u user -p database < test_data.sql"
echo "- Verify database has correct schema"
echo ""
echo "To customize this test:"
echo "- Edit BASE_URL at the top of this script"
echo "- Adjust RFC/phone values as needed"