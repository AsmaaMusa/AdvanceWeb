# Quick Testing Reference Card

## ✅ YOUR TEST DATA IS READY!

### 📊 Database Summary
- **Total Reports**: 13
- **Total Projects**: 5
- **Total Users**: 9

### 📈 Reports Breakdown

**By Type** (11 different types):
- Market Analysis: 2
- Operations: 2
- Benchmark, Business Strategy, Forecast, Growth Plan, Investor Brief, Marketing, Pricing, Research, SWOT: 1 each

**By Language**:
- Arabic: 7 reports
- English: 6 reports

**By Status**:
- Resolved: 5
- Reviewed: 4
- Pending: 3
- Rejected: 1

### 🏢 Top Projects
1. Najd Village Growth Plan - 3 reports
2. Urban Tech Positioning - 3 reports
3. Future SaaS Launch - 3 reports
4. Al-Safa Expansion - 2 reports
5. Green Cart Scale-Up - 2 reports

---

## 🚀 QUICK START TESTING

### 1. Open Dashboard
```
http://localhost:5173/admin/reports
```

### 2. Test Requirement #1: Filter Reports
**Dashboard → Reports Table → Filters**

Try these combinations:
```
✓ Filter by Type: "Market Analysis" (should show 2)
✓ Filter by Language: "Arabic" (should show 7)
✓ Combined: Type="Operations" + Language="English" (shows 1-2)
✓ Search: Type a project name like "Najd" or "Urban"
✓ Date Range: Pick any date range
```

**Expected**: Table updates immediately with filtered results

### 3. Test Requirement #2: View Projects
**Dashboard → Projects Overview Table**

**Verify**:
- ✓ All 5 projects listed
- ✓ Each shows correct report count
- ✓ Columns: Project Name, Business Name, Industry, Owner, Reports

**Cards to Check**:
- "Projects Total" card should show: **5**

### 4. Test Requirement #3: Daily & Weekly Charts
**Dashboard → Top Section → Charts**

**Daily Report Chart**:
- Shows last 7 days
- Bar height = reports generated that day
- Click bars to see tooltip with exact count

**Weekly Report Chart**:
- Shows last 4 weeks
- Line chart with points
- Hover for exact counts

**Cards to Check**:
- "Today's Reports" card (0-13)
- "This Week's Reports" card

---

## 🧪 SIMPLE TEST FLOWS

### Flow 1: Arabic Reports Only
1. Click Filters button
2. Set Language = "Arabic"
3. Results show 7 reports
4. "Filtered Results" card shows: **7**

### Flow 2: Market Analysis in English
1. Click Filters button
2. Set Type = "Market Analysis"
3. Set Language = "English"
4. Results show reports matching both filters
5. Export CSV for these filtered results

### Flow 3: Date Range Test
1. Pick From Date = 2 weeks ago
2. Pick To Date = Today
3. Table updates to show reports in that range
4. "Filtered Results" card updates

### Flow 4: Search Test
1. Type "Najd" in search box
2. Results filter to show Najd project reports
3. Try other project names: "Urban", "Future", "Al-Safa", "Green"

### Flow 5: Export Test
1. Set any filters (optional)
2. Click "Export CSV" button
3. File downloads
4. Open in Excel to verify data

---

## ✅ TESTING CHECKLIST

### Essential Tests (Must Pass)
- [ ] All 13 reports visible in table
- [ ] Filter by Type works (11 types available)
- [ ] Filter by Language works (Arabic, English)
- [ ] Combined filters work
- [ ] All 5 projects visible with correct counts
- [ ] Daily chart shows data
- [ ] Weekly chart shows data
- [ ] Export CSV works
- [ ] No errors in browser console (F12)

### Nice-to-Have Tests
- [ ] Sorting by clicking column headers works
- [ ] Pagination works if needed
- [ ] Tables responsive on mobile
- [ ] Charts responsive on mobile
- [ ] Hover effects work on cards
- [ ] Loading spinners appear during fetch

---

## 📝 EXAMPLE TEST SCENARIO

**Scenario: Find all Market Analysis reports**

Steps:
1. Open dashboard at http://localhost:5173/admin/reports
2. Click the Filters button (green filter icon)
3. Select "Market Analysis" from Type dropdown
4. Notice filter status shows "1 active filter"
5. Table should show 2 reports
6. "Filtered Results" card should show: **2**
7. Each report shows:
   - Report ID (e.g., RPT-001)
   - Title (e.g., "Market Trends Q2")
   - Project name
   - Type: "Market Analysis"
   - Language (Arabic or English)
   - Submitted By (owner name)
   - Date
   - Pages (number)
   - Status (Reviewed, Pending, etc.)

**Success Criteria**:
- ✓ Only 2 reports show
- ✓ Both are "Market Analysis" type
- ✓ Filter active indicator visible
- ✓ Reset Filters button appears

---

## 🔍 WHAT TO LOOK FOR

### Data Accuracy
- Report counts are accurate
- Project-to-report relationships are correct
- Filters match database queries
- Charts display accurate totals

### User Experience
- Filters respond quickly (debounced)
- Tables load with spinners
- Error messages are clear
- Empty states show when needed

### Performance
- Page loads in < 2 seconds
- Filters respond in < 1 second
- Charts render smoothly
- No lag on interactions

### Professional Quality
- Consistent styling and colors
- Proper spacing and alignment
- Readable fonts and contrast
- Responsive on all devices

---

## 🆘 TROUBLESHOOTING

### Dashboard shows "No data found"
- **Cause**: Filters are too strict
- **Fix**: Click "Reset Filters" button
- **Check**: Run `php artisan check:data` to verify data exists

### Charts are empty
- **Cause**: No reports with dates
- **Fix**: Data exists, charts may need time to load
- **Check**: Refresh browser page (Ctrl+R)

### Filters not updating
- **Cause**: Browser cache or timing issue
- **Fix**: Wait 1 second, manually refresh, or clear filters
- **Check**: Open F12 console, look for API errors

### Export CSV not working
- **Cause**: Pop-up blocker or network issue
- **Fix**: Allow pop-ups for this site
- **Check**: Check browser downloads folder

---

## 📊 DATA VERIFICATION

Run this command anytime to see current data:
```bash
cd backend
php artisan check:data
```

This shows:
- Total counts
- Breakdown by type, language, status
- Top projects by reports

---

## 🎯 SUCCESS INDICATORS

When testing is complete, you should see:

✅ Dashboard loads in <2 seconds
✅ All 13 reports visible
✅ All 5 projects visible with correct counts
✅ Filters work instantly
✅ Charts display data
✅ Export works
✅ No console errors
✅ Responsive on mobile
✅ All features match requirements

---

**Happy Testing! 🚀**

If issues arise, check browser console (F12) for error messages.
