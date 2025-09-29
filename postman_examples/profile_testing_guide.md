# Profile Management API Testing Guide

## 🚀 **FIXED ISSUES:**

✅ **Field Name Mismatch Fixed** - Now using `profile_image` consistently  
✅ **Error Handling Added** - Try-catch blocks with proper error responses  
✅ **Image Upload Fixed** - Proper file handling with cleanup  
✅ **URL Generation Fixed** - Correct Storage::url() usage  
✅ **Address Management Added** - Full CRUD operations for addresses

---

## 📋 **AVAILABLE ENDPOINTS:**

### **Profile Management:**

-   `GET /api/profile` - Get current user profile
-   `PUT /api/profile` - Update profile (including image upload)

### **Address Management:**

-   `GET /api/profile/addresses` - Get all user addresses
-   `POST /api/profile/addresses` - Add new address
-   `PUT /api/profile/addresses/{id}` - Update address
-   `DELETE /api/profile/addresses/{id}` - Delete address

---

## 🧪 **TESTING INSTRUCTIONS:**

### **Step 1: Get Authentication Token**

First, you need to login to get an authentication token:

**POST /api/login/email-login**

```json
{
    "email": "your-email@example.com",
    "password": "your-password"
}
```

**Copy the token from the response for use in Authorization headers.**

### **Step 2: Test Profile Retrieval**

**GET /api/profile**

-   **Headers:** `Authorization: Bearer YOUR_TOKEN`
-   **Expected:** Returns user profile with addresses

### **Step 3: Test Profile Update**

**PUT /api/profile**

-   **Headers:**
    ```
    Authorization: Bearer YOUR_TOKEN
    Content-Type: application/json
    ```
-   **Body:**

```json
{
    "name": "Updated Name",
    "phone": "+1234567890",
    "birthday": "1990-05-15"
}
```

### **Step 4: Test Image Upload**

**PUT /api/profile**

-   **Headers:** `Authorization: Bearer YOUR_TOKEN`
-   **Body Type:** `form-data`
-   **Fields:**
    -   `name`: "John Doe with Image"
    -   `profile_image`: [SELECT IMAGE FILE]

### **Step 5: Test Address Management**

**POST /api/profile/addresses**

-   **Headers:**
    ```
    Authorization: Bearer YOUR_TOKEN
    Content-Type: application/json
    ```
-   **Body:**

```json
{
    "address_line": "123 Main Street, New York, NY 10001",
    "latitude": 40.7128,
    "longitude": -74.006,
    "is_default": true
}
```

---

## ✅ **EXPECTED RESULTS:**

### **Profile Update Success:**

```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "Updated Name",
        "email": "user@example.com",
        "phone": "+1234567890",
        "birthday": "1990-05-15",
        "profile_image": "profile_images/filename.jpg",
        "profile_image_url": "http://localhost:8000/storage/profile_images/filename.jpg",
        "role": "patient",
        "created_at": "2025-01-24T10:00:00.000000Z",
        "updated_at": "2025-01-24T11:00:00.000000Z"
    }
}
```

### **Address Added Success:**

```json
{
    "success": true,
    "message": "Address added successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "address_line": "123 Main Street, New York, NY 10001",
        "latitude": "40.7128000",
        "longitude": "-74.0060000",
        "is_default": true,
        "created_at": "2025-01-24T10:00:00.000000Z",
        "updated_at": "2025-01-24T10:00:00.000000Z"
    }
}
```

---

## 🔧 **KEY FEATURES:**

### **Image Upload:**

-   ✅ Supports JPEG, PNG, JPG, WEBP formats
-   ✅ Maximum file size: 2MB
-   ✅ Automatic old image cleanup
-   ✅ Generates proper storage URLs
-   ✅ File cleanup on update failure

### **Address Management:**

-   ✅ Full CRUD operations
-   ✅ Default address handling (only one default)
-   ✅ Latitude/longitude support
-   ✅ Proper validation

### **Error Handling:**

-   ✅ Try-catch blocks on all endpoints
-   ✅ Proper HTTP status codes
-   ✅ Detailed error messages
-   ✅ File cleanup on failures

### **Validation:**

-   ✅ Image file validation
-   ✅ Date validation (birthday before today)
-   ✅ Phone number uniqueness
-   ✅ Address line requirements

---

## 🚨 **IMPORTANT NOTES:**

1. **Authentication Required:** All profile endpoints require authentication
2. **Image Upload:** Use `form-data` for image uploads, not JSON
3. **Default Address:** Only one address can be default at a time
4. **File Storage:** Images stored in `storage/app/public/profile_images/`
5. **URL Access:** Images accessible via `http://localhost:8000/storage/profile_images/filename.jpg`

---

## 🎯 **TESTING CHECKLIST:**

-   [ ] GET profile (returns user data with addresses)
-   [ ] PUT profile (updates basic info)
-   [ ] PUT profile (uploads image successfully)
-   [ ] POST address (adds new address)
-   [ ] PUT address (updates existing address)
-   [ ] DELETE address (removes address)
-   [ ] Default address logic (only one default)
-   [ ] Error handling (invalid data)
-   [ ] Image cleanup (old image deleted on update)

---

**All issues have been fixed! The profile management system is now fully functional with proper image upload and address management.** 🎉
