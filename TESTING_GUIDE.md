# Testing Guide - Reports & Projects Dashboard

## Prerequisites
✅ React frontend running on: http://localhost:5173/
✅ Laravel backend running on: http://127.0.0.1:8000/

## Step-by-Step Testing

### 1. **Access the Dashboard**
```
URL: http://localhost:5173/admin/reports
```
(You may need to login first depending on your auth setup)

---

## 2. **Test Requirement 1: View All Reports with Filters**

### 2.1 - View All Reports
- Navigate to the dashboard
- Scroll down to the **"Reports Overview"** table
- You should see all reports listed with columns:
  - Report ID
  - Title
  - Project
  - Type
  - Language
  - Submitted By
  - Date
  - Pages
  - Status

**Expected Result**: All reports display correctly in the table

### 2.2 - Test Filter by Date
1. Open the **Filters** panel (click the filter icon)
2. Enter a **From Date**: e.g., `2026-05-01`
3. Enter a **To Date**: e.g., `2026-05-15`
4. Results should show only reports generated in that date range
5. Check the "Filtered Results" card updates to show count

**Expected Result**: Only reports within the date range display

### 2.3 - Test Filter by Type
1. Click the **Type** dropdown in the filters
2. Select a specific report type (e.g., "Monthly Report")
3. Results filter to show only that type
4. Notice the filter status shows "1 active filter"

**Expected Result**: Only reports of selected type display

### 2.4 - Test Filter by Language
1. Click the **Language** dropdown
2. Select "Arabic" or "English"
3. Results filter to show only reports in that language
4. Badge styling shows language for each report

**Expected Result**: Only reports in selected language display

### 2.5 - Test Combined Filters
1. Set **Type** = "Market Analysis"
2. Set **Language** = "Arabic"
3. Set **Date Range** = Last 30 days
4. Filter status shows "3 active filters"

**Expected Result**: Only reports matching ALL filters display

### 2.6 - Test Search
1. Click the search field in the filters
2. Type a project name, business name, or report ID
3. Results filter in real-time (with slight delay)

**Expected Result**: Only matching records display

---

## 3. **Test Requirement 2: View All Projects**

### 3.1 - View Projects Overview
1. Scroll to the **"Projects Overview"** table
2. You should see all projects with columns:
   - Project Name
   - Business Name
   - Industry
   - Owner (Name & Email)
   - Reports Count (in a badge)

**Expected Result**: All projects display with their report counts

### 3.2 - Test Project Statistics
1. Look at the **"Projects Total"** summary card at the top
2. Verify the number matches the total project count in the table
3. Each project shows the exact count of associated reports

**Example**:
- Project A: 15 reports
- Project B: 8 reports
- Project C: 12 reports
- Total Projects Card: Shows total count

**Expected Result**: Total matches sum of all project reports

### 3.3 - Test Project Pagination
1. If there are more than 5 projects, pagination controls appear at bottom
2. Click the down arrow to go to next page
3. Click the up arrow to go to previous page
4. Page indicator shows "Page X of Y"

**Expected Result**: Pagination works smoothly

---

## 4. **Test Requirement 3: Daily & Weekly Report Generation Totals**

### 4.1 - View Daily Report Generation Chart
1. Find the **"Daily Report Generation"** chart
2. Shows a bar chart with last 7 days (Mon, Tue, Wed, etc.)
3. Each bar height represents number of reports generated that day

**Example Chart**:
```
Mon: 12 reports
Tue: 18 reports
Wed: 15 reports
Thu: 22 reports
Fri: 19 reports
Sat: 8 reports
Sun: 5 reports
```

**Testing**:
1. Hover over each bar to see exact count in tooltip
2. Verify counts match actual data
3. Check if today's count matches "Today's Reports" summary card

**Expected Result**: Chart displays correctly with accurate data

### 4.2 - View Weekly Report Generation Chart
1. Find the **"Weekly Report Generation"** chart
2. Shows a line chart with last 4 weeks
3. Each point shows weekly total

**Example Chart**:
```
Week 1 (Apr 28 - May 4): 87 reports
Week 2 (May 5 - May 11): 92 reports
Week 3 (May 12 - May 18): 78 reports
Week 4 (May 19 - May 25): 95 reports
```

**Testing**:
1. Hover over each point to see exact count
2. Check if current week matches "This Week" summary card
3. Verify trend line shows progression

**Expected Result**: Chart displays weekly totals accurately

### 4.3 - Test Chart Interaction
1. Hover over chart bars/lines to see tooltips
2. Charts are responsive - resize browser window
3. Charts reflow properly on mobile/tablet

**Expected Result**: Interactive tooltips show and charts are responsive

---

## 5. **Test Sorting (Bonus Feature)**

### 5.1 - Click Column Headers to Sort
1. In Reports table, click the **"Title"** header
2. Reports sort alphabetically by title
3. Click again to sort in reverse order
4. Indicator arrow shows sort direction

**Sortable Columns**:
- Report ID
- Title
- Project
- Type
- Language
- Date

**Expected Result**: Sorting works and arrow indicator updates

---

## 6. **Test Pagination (Bonus Feature)**

### 6.1 - Reports Pagination
1. Reports table shows 10 items per page
2. If more than 10 reports exist, pagination appears
3. Use arrow buttons to navigate pages
4. Page indicator shows current position

**Expected Result**: Pagination controls work smoothly

### 6.2 - Projects Pagination
1. Projects table shows 5 items per page
2. Navigation works as expected

**Expected Result**: Both tables paginate independently

---

## 7. **Test Export Functionality**

### 7.1 - Export CSV
1. Click the **"Export CSV"** button in top right
2. Button shows "Exporting..." during download
3. CSV file downloads automatically
4. Filename format: `filtered-reports-YYYY-MM-DD-HHMMSS.csv`

**Example Filename**: `filtered-reports-2026-05-11-150430.csv`

### 7.2 - Verify Exported Data
1. Open the CSV in Excel or text editor
2. Verify it contains:
   - Report ID
   - Title
   - Project
   - Business
   - Type
   - Language
   - Status
   - Pages
   - Submitted By
   - Email
   - Generated At

**Expected Result**: CSV contains all data with correct headers

### 7.3 - Test Export with Filters
1. Set some filters (Date range, Type, Language)
2. Click Export CSV
3. Open CSV and verify it only contains filtered data

**Expected Result**: Exported CSV matches current filters

---

## 8. **Test Summary Cards**

### 8.1 - Verify Each Card Updates
1. **Today's Reports**: Count of reports generated today
2. **This Week**: Count of reports generated this week (Mon-Sun)
3. **Filtered Results**: Count based on active filters
4. **Total Reports**: All reports on platform
5. **Projects Total**: All projects on platform

**Testing**:
1. Apply filters and watch "Filtered Results" card update
2. Check each card's subtitle for clarity
3. Hover over cards to see hover effect

**Expected Result**: All cards display correct values and are interactive

---

## 9. **Test Error Handling**

### 9.1 - Test with Empty Results
1. Set filters that return 0 results
2. Example: A very old date range with no data
3. Should show "No reports found" message
4. Cards should show 0
5. Tables should show empty state

**Expected Result**: Graceful empty state handling

### 9.2 - Test Loading States
1. Refresh the page
2. Should see loading spinner in tables
3. Summary cards should appear quickly
4. Charts should appear as data loads

**Expected Result**: Loading states display correctly

---

## 10. **Test Responsive Design**

### 10.1 - Desktop View (1920px+)
1. All 5 summary cards in one row
2. Charts side by side
3. Full table view

### 10.2 - Tablet View (768px - 1024px)
1. Summary cards in 3-2 layout
2. Charts stack vertically
3. Tables with horizontal scroll

### 10.3 - Mobile View (< 768px)
1. Summary cards stack vertically (1 column)
2. Charts full width
3. Tables scroll horizontally
4. All buttons and inputs remain accessible

**Testing**:
- Open browser DevTools (F12)
- Use responsive design mode
- Test at different breakpoints

**Expected Result**: Layout adapts smoothly at all sizes

---

## 11. **API Testing (Optional - Advanced)**

### 11.1 - Test Main Endpoint
```bash
GET http://127.0.0.1:8000/api/admin/reports-overview
```

### 11.2 - Test with Filters
```bash
GET http://127.0.0.1:8000/api/admin/reports-overview?type=Monthly&language=Arabic&from=2026-05-01&to=2026-05-15
```

### 11.3 - Test Export Endpoint
```bash
GET http://127.0.0.1:8000/api/admin/reports-overview/export?type=Monthly
```

### 11.4 - Test Analytics Endpoint
```bash
GET http://127.0.0.1:8000/api/admin/reports-overview/analytics
```

**Testing Tool**: Use Postman or curl

---

## Sample Test Data Checklist

Before testing, ensure you have:
- [ ] At least 10 reports in the database
- [ ] At least 3-5 projects
- [ ] Reports with different types
- [ ] Reports in different languages
- [ ] Reports across multiple dates
- [ ] Different report statuses (completed, pending, failed)
- [ ] Various page counts for reports

---

## Testing Checklist Summary

### Critical Features
- [ ] All reports display in table
- [ ] Date filter works
- [ ] Type filter works
- [ ] Language filter works
- [ ] Search works
- [ ] All projects display with correct counts
- [ ] Daily chart displays
- [ ] Weekly chart displays
- [ ] Export CSV works
- [ ] Pagination works
- [ ] Sorting works

### UI/UX Features
- [ ] Summary cards display correctly
- [ ] Colors and styling match design
- [ ] Hover effects work
- [ ] Loading spinners display
- [ ] Empty states show correctly
- [ ] Responsive design works

### Performance
- [ ] Page loads quickly
- [ ] Filters respond in real-time
- [ ] Charts render smoothly
- [ ] No console errors
- [ ] No console warnings

---

## Common Issues & Solutions

### Issue: No data showing
**Solution**: 
1. Check that reports exist in database
2. Verify you're authenticated as admin
3. Check browser console for errors (F12)

### Issue: Charts not displaying
**Solution**:
1. Ensure Recharts library is installed
2. Check network tab for API response
3. Verify data format is correct

### Issue: Filters not applying
**Solution**:
1. Check that filter values are being sent to API
2. Verify backend is processing filters correctly
3. Check Laravel logs: `storage/logs/laravel.log`

### Issue: Export not working
**Solution**:
1. Check that reports exist before exporting
2. Verify CORS headers allow file download
3. Check browser download folder

---

## Success Criteria

✅ All reports visible with all filters working
✅ All projects visible with correct report counts
✅ Daily chart shows last 7 days of generation counts
✅ Weekly chart shows last 4 weeks of generation counts
✅ No errors in console
✅ All exports working
✅ Responsive on all devices
✅ Sorting and pagination working

---

**Testing Status**: Ready to test!
