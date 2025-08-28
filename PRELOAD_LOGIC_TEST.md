# Test Plan: Registration Data Preloading Logic

## Overview
This document outlines the test plan to verify the updated registration data preloading logic that ensures:
1. RFC search is the primary method for company + representative data
2. Phone/email searches only preload guest data
3. Standardized success messages are shown based on data type

## Test Scenarios

### 1. RFC Search Tests

#### Test 1.1: Company Registration - RFC Found
**Input**: Valid RFC that exists in database  
**Expected Behavior**:
- Preloads all company fields (razon_social, nombre_comercial, etc.)
- Preloads representative fields (nombre_completo, email, telefono, puesto)
- Shows message: "✓ Datos de empresa y representante encontrados y precargados desde registros anteriores"

#### Test 1.2: Guest Registration - RFC Found
**Input**: Valid RFC that exists in database  
**Expected Behavior**:
- Preloads only basic representative fields for guest registration
- Sets ocupacion to "Dueño o Representante Legal"
- Shows message: "✓ Datos de empresa encontrados. Se han precargado los datos del representante para completar el registro como invitado."

#### Test 1.3: RFC Not Found
**Input**: Valid RFC format but not in database  
**Expected Behavior**:
- No fields preloaded
- Shows message: "ℹ RFC no encontrado en registros anteriores. Puedes continuar con el registro."

### 2. Phone Search Tests

#### Test 2.1: Phone Found - Guest Data Only
**Input**: Phone number that belongs to a guest (invitado)  
**Expected Behavior**:
- Preloads guest fields: nombre_completo, email, ocupacion, cargo_gubernamental
- Shows message: "✓ Datos de invitado encontrados y precargados desde registros anteriores"
- Shows modal if available, or fallback alert

#### Test 2.2: Phone Found - Company/Representative Data
**Input**: Phone number that belongs to empresa or representante  
**Expected Behavior**:
- Does NOT preload company/representative data
- Shows message: "ℹ Para empresas registradas, utilice la búsqueda por RFC para precargar datos completos. Puede continuar el registro manualmente."

#### Test 2.3: Phone Not Found
**Input**: Valid phone format but not in database  
**Expected Behavior**:
- No fields preloaded
- Shows message: "ℹ Teléfono no encontrado en registros anteriores. Puedes continuar con el registro."

### 3. Email Search Tests

#### Test 3.1: Email Found - Guest Data Only
**Input**: Email that belongs to a guest (invitado)  
**Expected Behavior**:
- Preloads guest fields: nombre_completo, telefono, ocupacion, cargo_gubernamental
- Shows message: "✓ Datos de invitado encontrados y precargados desde registros anteriores"
- Shows modal if available, or fallback alert

#### Test 3.2: Email Found - Representative Data
**Input**: Email that belongs to a representante  
**Expected Behavior**:
- Does NOT preload company/representative data
- Shows message: "ℹ Para empresas registradas, utilice la búsqueda por RFC para precargar datos completos. Puede continuar el registro manualmente."

#### Test 3.3: Email Not Found
**Input**: Valid email format but not in database  
**Expected Behavior**:
- No fields preloaded
- Shows message: "ℹ Email no encontrado en registros anteriores. Puedes continuar con el registro."

## Manual Testing Instructions

### Prerequisites
1. Access to registration forms (company-registration.php, guest-registration.php)
2. Test data in database:
   - At least one empresa with representante
   - At least one invitado record
   - Known RFC, phone, and email values

### Test Execution Steps

1. **Open company registration form**
   - Test RFC input with known company RFC
   - Test phone input with various data types
   - Test email input with various data types

2. **Open guest registration form**
   - Test RFC input with known company RFC
   - Test phone input with guest data
   - Test email input with guest data

3. **Verify field population**
   - Check that correct fields are populated
   - Check that incorrect fields are NOT populated
   - Verify messages match expected text

4. **Test edge cases**
   - Invalid formats (too short RFC, invalid email, etc.)
   - Connection errors (can be simulated by temporarily breaking API endpoint)
   - Empty responses

## Expected Results Summary

| Search Type | Data Found | Fields Preloaded | Message Type |
|-------------|------------|------------------|--------------|
| RFC (Company) | Company+Rep | All company & rep fields | Success (green) |
| RFC (Guest) | Company+Rep | Basic rep fields for guest | Success (green) |
| Phone | Guest only | Guest fields only | Success (green) |
| Phone | Company/Rep | None | Info (blue) - suggest RFC |
| Email | Guest only | Guest fields only | Success (green) |
| Email | Rep | None | Info (blue) - suggest RFC |
| Any | Not found | None | Info (blue) - can continue |

## Validation Checklist

- [ ] RFC search preloads complete company + representative data
- [ ] Phone search only preloads guest data (ignores company/rep data)
- [ ] Email search only preloads guest data (ignores rep data)
- [ ] All success messages are standardized with ✓ prefix
- [ ] Info messages guide users to correct search method
- [ ] No disruption to existing registration workflow
- [ ] Modal functionality preserved when available
- [ ] Fallback alerts work when modal not available
- [ ] Loading indicators work correctly
- [ ] Error handling maintains user experience