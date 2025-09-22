# AuxiApp Database Design Documentation

## 🎯 Overview

This document outlines the complete database schema for AuxiApp, a cleaning service marketplace platform. The database extends our existing user management system with marketplace-specific functionality.

## 🏗️ Database Architecture

### **Core Tables (7 new tables)**
1. **`jobs`** - Job listings posted by hosts
2. **`job_photos`** - Photos associated with jobs
3. **`offers`** - Offers and counter-offers from cleaners
4. **`job_assignments`** - Job assignments with QR codes
5. **`payments`** - Payment processing and payouts
6. **`disputes`** - Dispute management system
7. **`user_strikes`** - User penalty tracking
8. **`notifications`** - Multi-channel notification system

### **Existing Tables (Extended)**
- **`users`** - Already exists with comprehensive user management
- **Foreign key relationships** - All new tables link to existing users table

## 📊 Table Relationships

```
users (existing)
├── jobs (1:many) - Host creates multiple jobs
├── job_photos (1:many) - Users upload photos
├── offers (1:many) - Cleaners make offers
├── job_assignments (1:many) - Cleaners get assignments
├── payments (1:many) - Users involved in payments
├── disputes (1:many) - Users involved in disputes
├── user_strikes (1:many) - Users receive strikes
└── notifications (1:many) - Users receive notifications

jobs
├── job_photos (1:many) - Jobs have multiple photos
├── offers (1:many) - Jobs receive multiple offers
├── job_assignments (1:1) - Jobs get one assignment
├── payments (1:many) - Jobs have multiple payments
└── disputes (1:many) - Jobs can have disputes
```

## 🗃️ Detailed Table Specifications

### **1. JOBS Table**
**Purpose:** Store cleaning job listings posted by hosts

**Key Fields:**
- `host_id` - Links to users table (host who posted)
- `title`, `description` - Job details
- `address`, `city`, `state` - Location information
- `latitude`, `longitude` - GPS coordinates for mapping
- `scheduled_date`, `scheduled_time` - When job should be done
- `estimated_duration` - Duration in minutes
- `rooms` - JSON array of room types and counts
- `extras` - JSON array of extra services
- `pets` - Boolean for pet presence
- `suggested_price`, `final_price` - Pricing information
- `status` - Job lifecycle status
- `boost_amount` - Amount paid to boost visibility

**Status Flow:**
```
draft → open → offers_received → assigned → in_progress → completed
  ↓       ↓           ↓
cancelled ← disputed ←
```

### **2. JOB_PHOTOS Table**
**Purpose:** Store photos associated with jobs

**Key Fields:**
- `job_id` - Links to jobs table
- `photo_url` - URL to stored image
- `photo_type` - before, after, reference, dispute
- `uploaded_by` - User who uploaded the photo
- `is_primary` - Primary photo for job listing
- `sort_order` - Display order

### **3. OFFERS Table**
**Purpose:** Store offers and counter-offers from cleaners

**Key Fields:**
- `job_id` - Links to jobs table
- `cleaner_id` - Links to users table (cleaner)
- `offer_type` - accept (original price) or counter (different price)
- `amount` - Offer amount
- `message` - Optional message from cleaner
- `status` - pending, accepted, declined, expired, cancelled
- `expires_at` - 3-hour expiration timer

**Business Rules:**
- Only one active offer per cleaner per job
- Offers expire after 3 hours
- Host can accept/decline offers

### **4. JOB_ASSIGNMENTS Table**
**Purpose:** Track job assignments with QR code verification

**Key Fields:**
- `job_id` - Links to jobs table
- `cleaner_id` - Links to users table (assigned cleaner)
- `qr_code` - Unique QR code for job verification
- `passcode` - Additional security code
- `status` - assigned, started, completed, cancelled, no_show
- `started_at` - When cleaner started the job
- `completed_at` - When cleaner completed the job
- `completion_notes` - Notes from cleaner
- `completion_photos` - JSON array of completion photos
- `host_confirmed_at` - Host confirmation timestamp
- `auto_confirmed_at` - Auto-confirmation after 24h

**Business Rules:**
- QR code generated when job is assigned
- Late start notification after 30 minutes
- Auto-cancel if no start after 2 hours
- Auto-confirm after 24h if no host response

### **5. PAYMENTS Table**
**Purpose:** Track all payment transactions

**Key Fields:**
- `job_id` - Links to jobs table
- `host_id`, `cleaner_id` - Payment participants
- `payment_type` - job_payment, refund, payout, tip
- `amount` - Total transaction amount
- `platform_fee` - 15% + $30 MXN flat fee
- `cleaner_payout` - Amount paid to cleaner
- `refund_amount` - Amount refunded to host
- `refund_percentage` - 25%, 50%, or 100%
- `status` - pending, processing, completed, failed, refunded, disputed
- Stripe integration fields

**Fee Structure:**
- Platform fee: 15% of job amount + $30 MXN flat fee
- Cleaner payout: Job amount - platform fee
- Refund tiers: 100% (≥24h), 50% (<24h), 25% (dispute resolution)

### **6. DISPUTES Table**
**Purpose:** Manage dispute resolution process

**Key Fields:**
- `job_id` - Links to jobs table
- `host_id`, `cleaner_id` - Dispute participants
- `dispute_type` - quality, no_show, damage, incomplete, other
- `reason` - Detailed dispute description
- `host_evidence`, `cleaner_evidence` - JSON arrays of evidence
- `status` - open, under_review, resolved, closed
- `admin_id` - Admin handling the dispute
- `resolution` - Final resolution decision
- `refund_amount` - Refund amount if applicable
- `cleaner_strike`, `host_strike` - Whether participants get strikes

**Business Rules:**
- Host has 48h to dispute after job completion
- Admin has 72h to resolve disputes
- 3 strikes = user suspension

### **7. USER_STRIKES Table**
**Purpose:** Track user penalties and strikes

**Key Fields:**
- `user_id` - Links to users table
- `job_id`, `dispute_id` - Related job or dispute
- `reason` - Strike reason (no_show, late_start, poor_quality, etc.)
- `description` - Detailed description
- `admin_id` - Admin who issued the strike
- `is_active` - Whether strike is currently active
- `expires_at` - When strike expires (if applicable)

**Business Rules:**
- 3 active strikes = user suspension
- Strikes can be removed by admin
- Strikes can have expiration dates

### **8. NOTIFICATIONS Table**
**Purpose:** Multi-channel notification system

**Key Fields:**
- `user_id` - Links to users table
- `job_id` - Related job (if applicable)
- `type` - Notification type (offer_received, job_assigned, etc.)
- `title`, `message` - Notification content
- `data` - JSON data for notification
- `channels` - JSON array of channels (email, sms, in_app)
- `email_sent`, `sms_sent` - Delivery status
- `in_app_read` - In-app read status
- `scheduled_at` - When to send (for scheduled notifications)

## 🔄 Business Logic Implementation

### **Job Lifecycle**
1. **Creation** - Host creates job (status: draft)
2. **Publishing** - Job goes live (status: open)
3. **Offers** - Cleaners submit offers (status: offers_received)
4. **Assignment** - Host accepts offer (status: assigned)
5. **Execution** - Cleaner starts job (status: in_progress)
6. **Completion** - Job completed (status: completed)

### **Payment Flow**
1. **Host Payment** - Charged when accepting offer
2. **Platform Fee** - 15% + $30 MXN deducted
3. **Cleaner Payout** - Released after job completion
4. **Refunds** - Based on cancellation timing

### **Dispute Resolution**
1. **Dispute Creation** - Host has 48h to dispute
2. **Admin Review** - Admin has 72h to resolve
3. **Resolution** - Refund amount and strikes determined
4. **Closure** - Dispute marked as resolved

## 📈 Performance Optimizations

### **Indexes**
- **Primary keys** - All tables have auto-increment primary keys
- **Foreign keys** - All relationships properly indexed
- **Composite indexes** - For common query patterns
- **Status indexes** - For filtering by status
- **Date indexes** - For time-based queries

### **Views**
- **`active_jobs`** - Currently active job listings
- **`cleaner_earnings`** - Cleaner earnings summary
- **`platform_analytics`** - Platform performance metrics

## 🔒 Data Integrity

### **Foreign Key Constraints**
- All relationships have proper foreign key constraints
- Cascade deletes where appropriate
- Set NULL for optional relationships

### **Data Validation**
- ENUM fields for controlled values
- JSON fields for flexible data structures
- Proper data types for all fields
- Comments for field documentation

## 🚀 Scalability Considerations

### **Partitioning Strategy**
- Consider partitioning by date for large tables
- Archive old completed jobs
- Clean up expired offers and notifications

### **Caching Strategy**
- Cache active job listings
- Cache user profiles and preferences
- Cache platform statistics

### **API Integration**
- Stripe Connect for payments
- SMS service for notifications
- Email service for communications
- Maps API for location services

## 📱 Mobile App Preparation

### **API-Ready Structure**
- All tables designed for API consumption
- JSON fields for flexible data exchange
- Proper status tracking for real-time updates
- Notification system for push notifications

### **Offline Capability**
- QR codes for offline job verification
- Passcode system for security
- Photo upload with offline queuing

## 🔧 Migration Strategy

### **CodeIgniter Migrations**
- 8 migration files created
- Proper up/down methods
- Foreign key constraints
- Index creation

### **Manual SQL Setup**
- Complete SQL file for manual setup
- Sample data for testing
- Views for common queries
- Performance indexes

## 📋 Next Steps

1. **Run Migrations** - Execute migration files
2. **Test Schema** - Verify all relationships work
3. **Create Models** - Build CodeIgniter models for each table
4. **API Development** - Create REST API endpoints
5. **Frontend Integration** - Connect UI to database

This database design provides a solid foundation for the AuxiApp marketplace while leveraging our existing user management system.
