# FAQ API Testing Guide

## 🚀 **API Endpoints Available**

Your server is running at: `http://127.0.0.1:8000`

### **Available Endpoints:**

-   `GET /api/faqs` - Get all active FAQs (ordered)
-   `GET /api/faqs/all` - Get all FAQs (including inactive)
-   `GET /api/faqs/{id}` - Get specific FAQ by ID
-   `POST /api/faqs` - Create new FAQ
-   `PUT /api/faqs/{id}` - Update FAQ
-   `DELETE /api/faqs/{id}` - Delete FAQ

---

## 📝 **POSTMAN TESTING EXAMPLES**

### **1. GET Active FAQs (Main Endpoint)**

**Request:**

-   **Method:** `GET`
-   **URL:** `http://127.0.0.1:8000/api/faqs`
-   **Headers:** None required
-   **Body:** None

**Expected Response:**

```json
{
    "success": true,
    "message": "FAQs retrieved successfully",
    "data": []
}
```

### **2. POST Create FAQ - Basic Example**

**Request:**

-   **Method:** `POST`
-   **URL:** `http://127.0.0.1:8000/api/faqs`
-   **Headers:**
    ```
    Content-Type: application/json
    Accept: application/json
    ```
-   **Body (raw JSON):**

```json
{
    "question": "How do I book an appointment with a doctor?",
    "answer": "To book an appointment, simply log into your account, click on 'Book Appointment', select your preferred doctor, choose an available time slot, and confirm your booking. You'll receive a confirmation email with all the details.",
    "order": 1,
    "status": "active"
}
```

**Expected Response (201):**

```json
{
    "success": true,
    "message": "FAQ created successfully",
    "data": {
        "id": 1,
        "question": "How do I book an appointment with a doctor?",
        "answer": "To book an appointment, simply log into your account...",
        "order": 1,
        "status": "active",
        "status_readable": "Active",
        "created_at": "2025-01-24T10:00:00.000000Z",
        "updated_at": "2025-01-24T10:00:00.000000Z"
    }
}
```

### **3. POST Create FAQ - Without Order (Auto-assigned)**

**Request:**

-   **Method:** `POST`
-   **URL:** `http://127.0.0.1:8000/api/faqs`
-   **Headers:**
    ```
    Content-Type: application/json
    Accept: application/json
    ```
-   **Body (raw JSON):**

```json
{
    "question": "What payment methods do you accept?",
    "answer": "We accept all major credit cards (Visa, MasterCard, American Express, Discover), debit cards, PayPal, Apple Pay, and Google Pay. All payments are processed securely through our encrypted payment system.",
    "status": "active"
}
```

### **4. POST Create FAQ - Inactive Status**

**Request:**

-   **Method:** `POST`
-   **URL:** `http://127.0.0.1:8000/api/faqs`
-   **Headers:**
    ```
    Content-Type: application/json
    Accept: application/json
    ```
-   **Body (raw JSON):**

```json
{
    "question": "This is an inactive FAQ for testing",
    "answer": "This FAQ has inactive status and should not appear in the main FAQ list when fetching active FAQs only.",
    "order": 10,
    "status": "inactive"
}
```

### **5. GET All FAQs (Including Inactive)**

**Request:**

-   **Method:** `GET`
-   **URL:** `http://127.0.0.1:8000/api/faqs/all`
-   **Headers:** None required
-   **Body:** None

**Expected Response:**

```json
{
    "success": true,
    "message": "All FAQs retrieved successfully",
    "data": [
        {
            "id": 1,
            "question": "How do I book an appointment with a doctor?",
            "answer": "To book an appointment...",
            "order": 1,
            "status": "active",
            "status_readable": "Active",
            "created_at": "2025-01-24T10:00:00.000000Z",
            "updated_at": "2025-01-24T10:00:00.000000Z"
        },
        {
            "id": 2,
            "question": "This is an inactive FAQ for testing",
            "answer": "This FAQ has inactive status...",
            "order": 10,
            "status": "inactive",
            "status_readable": "Inactive",
            "created_at": "2025-01-24T10:00:00.000000Z",
            "updated_at": "2025-01-24T10:00:00.000000Z"
        }
    ]
}
```

### **6. GET Specific FAQ**

**Request:**

-   **Method:** `GET`
-   **URL:** `http://127.0.0.1:8000/api/faqs/1`
-   **Headers:** None required
-   **Body:** None

### **7. PUT Update FAQ**

**Request:**

-   **Method:** `PUT`
-   **URL:** `http://127.0.0.1:8000/api/faqs/1`
-   **Headers:**
    ```
    Content-Type: application/json
    Accept: application/json
    ```
-   **Body (raw JSON):**

```json
{
    "question": "Updated: How do I book an appointment with a doctor?",
    "answer": "Updated answer: To book an appointment, simply log into your account, click on 'Book Appointment', select your preferred doctor, choose an available time slot, and confirm your booking. You'll receive a confirmation email with all the details. You can also use our mobile app for easier booking."
}
```

### **8. PUT Update FAQ Order**

**Request:**

-   **Method:** `PUT`
-   **URL:** `http://127.0.0.1:8000/api/faqs/1`
-   **Headers:**
    ```
    Content-Type: application/json
    Accept: application/json
    ```
-   **Body (raw JSON):**

```json
{
    "order": 99
}
```

### **9. PUT Update FAQ Status**

**Request:**

-   **Method:** `PUT`
-   **URL:** `http://127.0.0.1:8000/api/faqs/1`
-   **Headers:**
    ```
    Content-Type: application/json
    Accept: application/json
    ```
-   **Body (raw JSON):**

```json
{
    "status": "inactive"
}
```

### **10. DELETE FAQ**

**Request:**

-   **Method:** `DELETE`
-   **URL:** `http://127.0.0.1:8000/api/faqs/1`
-   **Headers:** None required
-   **Body:** None

**Expected Response:**

```json
{
    "success": true,
    "message": "FAQ deleted successfully",
    "data": null
}
```

---

## 🎯 **TESTING SCENARIOS**

### **Scenario 1: Create Multiple FAQs**

1. Create 5 different FAQs with different orders
2. Test GET /api/faqs to see them in order
3. Test GET /api/faqs/all to see all FAQs

### **Scenario 2: Test Ordering**

1. Create FAQs with orders: 5, 2, 8, 1, 3
2. GET /api/faqs should return them in order: 1, 2, 3, 5, 8

### **Scenario 3: Test Status Filtering**

1. Create some FAQs with "active" status
2. Create some FAQs with "inactive" status
3. GET /api/faqs should return only active FAQs
4. GET /api/faqs/all should return all FAQs

### **Scenario 4: Test Auto-Order Assignment**

1. Create FAQs without specifying order
2. System should auto-assign order numbers
3. Verify the order assignment is correct

---

## 📊 **SAMPLE FAQ CONTENT FOR TESTING**

### **General Questions:**

```json
{
    "question": "How do I reset my password?",
    "answer": "Click on 'Forgot Password' on the login page, enter your email address, and follow the instructions in the email you receive. Make sure to check your spam folder if you don't see the email within a few minutes.",
    "order": 1,
    "status": "active"
}
```

### **Payment Questions:**

```json
{
    "question": "What are your business hours?",
    "answer": "Our platform is available 24/7 for booking appointments and accessing your account. However, doctor availability varies by provider. Most doctors are available during standard business hours, but some offer evening and weekend appointments.",
    "order": 2,
    "status": "active"
}
```

### **Technical Questions:**

```json
{
    "question": "Is there a mobile app available?",
    "answer": "Yes, our mobile app is available for both iOS and Android devices. You can download it from the App Store or Google Play Store. The app provides all the same features as our website, including appointment booking, messaging with doctors, and accessing your medical records.",
    "order": 3,
    "status": "active"
}
```

---

## ✅ **EXPECTED RESULTS**

-   **First GET requests** → Should return empty array `[]`
-   **After creating FAQs** → Should return FAQs in proper order
-   **Active FAQs only** → GET /api/faqs returns only active FAQs
-   **All FAQs** → GET /api/faqs/all returns all FAQs regardless of status
-   **Ordering** → FAQs should be returned in ascending order by the `order` field
-   **Auto-assignment** → FAQs without order should get next available order number

---

## 🔧 **QUICK TEST COMMANDS (cURL)**

```bash
# Test GET Active FAQs
curl -X GET http://127.0.0.1:8000/api/faqs

# Test POST Create FAQ
curl -X POST http://127.0.0.1:8000/api/faqs \
  -H "Content-Type: application/json" \
  -d '{"question":"How do I book an appointment?","answer":"To book an appointment, simply log into your account...","order":1,"status":"active"}'

# Test GET All FAQs
curl -X GET http://127.0.0.1:8000/api/faqs/all
```

---

**Ready to test! Start with creating a few FAQs and then test the GET endpoints to see them in action!** 🎉
