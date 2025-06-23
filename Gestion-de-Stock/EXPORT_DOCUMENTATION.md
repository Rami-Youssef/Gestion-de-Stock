# Export Functionality Documentation

## Overview

This Laravel application now includes comprehensive export functionality for all main data tables. Users can export data in both Excel (.xlsx) and PDF formats with two scope options:

- **Current Page**: Exports only the data visible on the current page (respects pagination)
- **All Data**: Exports all data matching the applied filters

## Features

### Supported Tables
- **Categories** (`CategoriesExport`)
- **Products** (`ProduitsExport`) 
- **Stock Movements** (`MouvementsExport`)
- **Users** (`UsersExport`)

### Export Formats
- **Excel (.xlsx)**: Professional spreadsheet format with headers, styling, and proper data formatting
- **PDF**: Formatted document with company branding, filters summary, and professional layout

### Filter Respect
All exports respect the current filter states:
- Search terms
- Category filters (for products)
- Date ranges (for stock movements)
- Role filters (for users)
- Sorting preferences

## Technical Implementation

### Backend Architecture

#### 1. Export Classes (`app/Exports/`)
Each export class implements multiple interfaces:
- `FromQuery`: Builds the database query
- `WithHeadings`: Defines column headers
- `WithMapping`: Formats row data
- `WithStyles`: Applies Excel styling

#### 2. ExportableTrait (`app/Http/Controllers/Traits/`)
Reusable trait that provides:
- `exportExcel()`: Handles Excel export logic
- `exportPdf()`: Handles PDF export logic
- Consistent file naming with timestamps
- Scope management (current vs all data)

#### 3. PDF Templates (`resources/views/exports/`)
Professional PDF layouts with:
- Company header and branding
- Applied filters summary
- Formatted data tables
- Footer with statistics
- Color-coded elements

#### 4. Routes (`routes/web.php`)
Export routes for each controller:
```php
Route::get('categories/export/excel', [CategorieController::class, 'exportExcel']);
Route::get('categories/export/pdf', [CategorieController::class, 'exportPdf']);
```

### Frontend Implementation

#### 1. Export Dropdown UI
Each index page features a professional export dropdown with:
- Current page export options
- All data export options  
- Clear visual separation
- File format icons

#### 2. JavaScript Enhancement (`public/assets/js/exports.js`)
- Loading states for export buttons
- User feedback notifications
- Error handling
- Dropdown auto-close

#### 3. CSS Styling (`public/assets/css/custom.css`)
- Professional dropdown styling
- Loading animations
- Responsive design
- Bootstrap integration

## Usage

### For End Users

1. **Navigate** to any data table (Categories, Products, Stock Movements, Users)
2. **Apply filters** as needed (search, category, date range, etc.)
3. **Click the Export dropdown** (green button with download icon)
4. **Choose export scope**:
   - "Page Actuelle" for current page only
   - "Toutes les Données" for complete filtered dataset
5. **Select format**: Excel or PDF
6. **Download** will start automatically

### For Developers

#### Adding Export to New Tables

1. **Create Export Class**:
```php
php artisan make:export NewTableExport
```

2. **Implement Required Interfaces**:
```php
class NewTableExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    // Implementation
}
```

3. **Add Trait to Controller**:
```php
use App\Http\Controllers\Traits\ExportableTrait;

class NewController extends Controller 
{
    use ExportableTrait;
    
    public function exportExcel(Request $request)
    {
        return $this->exportExcel($request, NewTableExport::class, 'TableName');
    }
}
```

4. **Create PDF Template**:
Create `resources/views/exports/newtable-pdf.blade.php`

5. **Add Routes**:
```php
Route::get('newtable/export/excel', [NewController::class, 'exportExcel']);
Route::get('newtable/export/pdf', [NewController::class, 'exportPdf']);
```

6. **Add UI to Index View**:
Copy the export dropdown from existing index views.

## Dependencies

### Required Packages
- `maatwebsite/excel`: ^3.1 (Excel export functionality)
- `barryvdh/laravel-dompdf`: ^3.1 (PDF generation)
- `dompdf/dompdf`: ^3.1 (PDF rendering engine)

### Configuration Files
- `config/excel.php`: Excel export settings
- `config/dompdf.php`: PDF generation settings

## File Naming Convention

Exported files follow this pattern:
```
[TableName]_[Scope]_[Timestamp].[Extension]

Examples:
- Categories_Current_20240623_143052.xlsx
- Produits_All_20240623_143105.pdf
- Mouvements-Stock_Current_20240623_143120.xlsx
```

## Performance Considerations

- **Memory Usage**: Large datasets are processed in chunks
- **Timeout Handling**: Export operations have extended time limits
- **File Size**: PDF exports include pagination for large datasets
- **Caching**: Route caching is recommended for production

## Error Handling

- Invalid requests return appropriate HTTP errors
- Missing data shows user-friendly messages  
- Export failures are logged and reported
- Graceful degradation for unsupported browsers

## Security

- All exports respect user permissions
- Admin-only data (users) requires admin role
- CSRF protection on all export routes
- Input validation and sanitization

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive design
- Progressive enhancement
- Fallback for disabled JavaScript

## Future Enhancements

Potential improvements:
- Email export delivery
- Scheduled export jobs
- Custom export templates
- Additional file formats (CSV, ODS)
- Export history tracking
- Bulk export operations
