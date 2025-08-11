# Test to Chapter Mapping System

## Overview
The test mapping system has been upgraded from **class-based mapping** to **chapter-based mapping** to provide more granular control and better organization of tests within the educational hierarchy.

## New Hierarchy Structure
```
Class → Stream → Subject → Section → Chapter
```

Tests are now mapped directly to specific chapters, allowing for:
- More precise content organization
- Better student experience with chapter-specific tests
- Improved content management for educators

## Changes Made

### 1. Database Schema Updates
- **New Table**: `test_chapters_map`
  - `id` (Primary Key)
  - `test_id` (Foreign Key to tests)
  - `chapter_id` (Foreign Key to chapters)
  - `created_at` (Timestamp)
  - Unique constraint on (test_id, chapter_id)

- **Legacy Table**: `test_classes_map` (preserved for backward compatibility)

### 2. New Features

#### Test to Chapter Mapping Interface
- **File**: `frontend/test/map_test_to_chapter.php`
- **Features**:
  - Select tests from dropdown
  - Select chapters with full hierarchy path display
  - Prevent duplicate mappings
  - View recent mappings

#### Chapter Mapping Management
- **File**: `frontend/test/edit_test_chapter_map.php`
- **Features**:
  - View all test-chapter mappings
  - Delete mappings
  - Search functionality
  - Organized by class/stream/subject hierarchy

### 3. Updated Files

#### Core Files Modified:
1. **backend/utils.php**
   - Added `getAllChaptersWithHierarchy()`
   - Added `getTestsForChapter($chapter_id)`
   - Added `getChaptersForTest($test_id)`

2. **frontend/test/view_test.php**
   - Updated to show chapter mappings instead of class mappings
   - Added chapter assignment display section

3. **frontend/test/list_tests.php**
   - Updated to show chapter count instead of class count
   - Modified mapping links to point to chapter mapping

4. **frontend/noteslist.php**
   - Updated to show chapter-specific tests
   - Changed from class-based to chapter-based test retrieval

5. **frontend/test/delete_test.php**
   - Added cleanup for chapter mappings during test deletion

6. **frontend/test/admin_take_test.php**
   - Updated to use chapter-based mapping count

## How to Use the New System

### 1. Mapping Tests to Chapters

1. **Navigate to Test Management**:
   - Go to Admin Panel → Tests → List Tests
   - Or directly to: `frontend/test/list_tests.php`

2. **Create New Mapping**:
   - Click "Map to Chapters" button for any test
   - Or go to: `frontend/test/map_test_to_chapter.php`

3. **Select Test and Chapter**:
   - Choose the test from dropdown
   - Select the target chapter from the hierarchical dropdown
   - Chapter paths are displayed as: Class → Stream → Subject → Section → Chapter

4. **Submit Mapping**:
   - Click "Assign Test" button
   - System prevents duplicate mappings automatically

### 2. Managing Existing Mappings

1. **View All Mappings**:
   - Go to: `frontend/test/edit_test_chapter_map.php`
   - Or click "Manage Mappings" from the mapping interface

2. **Search and Filter**:
   - Use the search box to find specific tests or chapters
   - Mappings are organized by class/stream/subject for easy navigation

3. **Delete Mappings**:
   - Click the delete button (trash icon) next to any mapping
   - Confirm deletion in the popup dialog

### 3. Viewing Test Assignments

1. **Test Details Page**:
   - Go to: `frontend/test/view_test.php?test_id=X`
   - Shows all chapters the test is mapped to
   - Displays full hierarchy path for each mapping

2. **Chapter-Specific Tests**:
   - Navigate through: Class → Stream → Subject → Section → Chapter
   - Tests are now shown only for the specific chapter you're viewing
   - More relevant and focused test experience for students

## Benefits of Chapter-Based Mapping

### For Educators:
- **Granular Control**: Map tests to specific chapters instead of entire classes
- **Better Organization**: Tests are contextually relevant to chapter content
- **Flexible Mapping**: One test can be mapped to multiple chapters if needed
- **Improved Analytics**: Track performance at chapter level

### For Students:
- **Focused Practice**: See only tests relevant to current chapter
- **Better Navigation**: Tests appear when studying specific chapters
- **Contextual Learning**: Tests are directly related to chapter content
- **Progressive Learning**: Chapter-by-chapter test progression

### For Administrators:
- **Enhanced Reporting**: More detailed mapping analytics
- **Content Management**: Better control over test distribution
- **Quality Assurance**: Ensure tests match chapter content

## Migration Notes

### Backward Compatibility
- Old class-based mappings (`test_classes_map`) are preserved
- System can operate with both mapping types during transition
- No data loss during the upgrade

### Migration Path
1. **Immediate**: New tests should use chapter-based mapping
2. **Gradual**: Existing class-based mappings can be migrated over time
3. **Legacy Support**: Old mappings continue to work but are not actively used

## Technical Details

### Database Relationships
```sql
tests (test_id) → test_chapters_map (test_id)
chapters (ID) → test_chapters_map (chapter_id)
```

### Key Functions
- `getAllChaptersWithHierarchy()`: Returns chapters with full path
- `getTestsForChapter($chapter_id)`: Gets tests for specific chapter
- `getChaptersForTest($test_id)`: Gets chapters mapped to a test

### File Structure
```
frontend/test/
├── map_test_to_chapter.php      # New mapping interface
├── edit_test_chapter_map.php    # Mapping management
├── view_test.php                # Updated to show chapter mappings
├── list_tests.php               # Updated for chapter counts
└── delete_test.php              # Updated cleanup logic

backend/
└── utils.php                    # New helper functions added
```

## Future Enhancements

### Possible Improvements:
1. **Bulk Mapping**: Map multiple tests to chapters at once
2. **Import/Export**: Backup and restore mapping configurations
3. **Auto-Suggestion**: Suggest chapters based on test content
4. **Analytics Dashboard**: Chapter-wise test performance metrics
5. **Student Progress**: Chapter completion tracking based on tests

## Support and Troubleshooting

### Common Issues:
1. **Duplicate Mapping**: System prevents this automatically
2. **Missing Chapters**: Ensure chapter hierarchy is properly set up
3. **Test Not Showing**: Check if test is mapped to current chapter
4. **Performance**: Use database indexes for large datasets

### Debugging:
- Check browser console for JavaScript errors
- Verify database connections
- Ensure all foreign key relationships are intact
- Check file permissions for PHP files

---
*This system provides a more granular and effective way to organize tests within your educational platform. The chapter-based approach aligns better with how students learn and how educators structure their content.*
