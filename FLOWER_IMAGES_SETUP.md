# Flower Images Setup Guide

## Overview
Your theme now supports **10 flower types** with prices from **€10 to €40**. Placeholder images are included, and you just need to replace them with your own product photos.

## Flower Types & Image Locations

All flower images should be PNG files located in:
```
/assets/images/flowers/
```

### Supported Flower Types

| Flower Type | Image File | Keyword Match | Price Range | Status |
|-------------|-----------|---------------|------------|--------|
| Lavender | `lavender.png` | "lavender" | €10–€15 | ✅ Placeholder |
| Carnation | `carnations.png` | "carnation" | €12–€18 | ✅ Placeholder |
| Lily | `lilies.png` | "lily" or "lilies" | €15–€20 | ✅ Placeholder |
| Tulip | `tulips.png` | "tulip" | €18–€25 | ✅ Placeholder |
| Sunflower | `sunflowers.png` | "sunflower" | €20–€30 | ✅ Placeholder |
| Dahlia | `dahlias.png` | "dahlia" | €24–€35 | ✅ Placeholder |
| Peony | `peonies.png` | "peony" or "peonies" | €28–€40 | ✅ Placeholder |
| Rose | `roses.png` | "rose" or "roses" | €32–€40 | ✅ Placeholder |
| Orchid | `orchids.png` | "orchid" | €36–€40 | ✅ Placeholder (NEW) |
| Hydrangea | `hydrangeas.png` | "hydrangea" | €40 | ✅ Placeholder (NEW) |

## How the Image Matching Works

When you create a flower product post in WordPress, the theme automatically detects which image to use by checking if the **post title or content** contains the flower keyword.

### Examples:
- Post titled "**Classic Red Roses**" → Uses `roses.png`
- Post with "Fresh **pink tulips** with..." → Uses `tulips.png`
- Post titled "Bright **Sunflower** Sunshine" → Uses `sunflowers.png` ✨ NEW
- Post titled "Luxe **Dahlia** Blush" → Uses `dahlias.png` ✨ NEW
- Post titled "Romantic **Peony** Arrangement" → Uses `peonies.png` ✨ NEW

## How to Replace Placeholder Images

1. **Prepare your images:**
   - Format: PNG
   - Recommended size: 260×260 pixels (or larger—WordPress will scale)
   - Keep aspect ratio square for best appearance in cards

2. **Replace via File Manager/FTP:**
   - Navigate to `/wp-content/themes/wordpress.project/assets/images/flowers/`
   - Upload your PNG file with the exact filename (e.g., `roses.png`, `sunflowers.png`)
   - Confirm it overwrites the placeholder

3. **Create product posts in WordPress:**
   - Go to WordPress Admin → Posts → Add New
   - Title: e.g., "Classic Red Roses" or "Bright Sunflower Sunshine"
   - Add the flower keyword in title or content
   - Assign to "Flowers" category
   - Set price via custom field: `price` = `$39.00`
   - Publish

4. **Optional: Add featured image**
   - If you upload a featured image, it takes priority over the fallback
   - Fallback image only shows if no featured image is set

## New Demo Products (Pre-configured)

These 10 demo flower products are automatically created (and regenerated) on theme activation:

1. ✅ Lavender Serenity (€10.00) → `lavender.png`
2. ✅ Carnation Charm (€12.50) → `carnations.png`
3. ✅ White Lily Harmony (€15.00) → `lilies.png`
4. ✅ Soft Pink Tulips (€18.00) → `tulips.png`
5. ✅ Bright Sunflower Sunshine (€20.00) → `sunflowers.png`
6. ✅ Luxe Dahlia Blush (€24.00) → `dahlias.png`
7. ✅ Romantic Peony Arrangement (€28.00) → `peonies.png`
8. ✅ Classic Red Roses (€32.00) → `roses.png`
9. ✨ **Elegant Orchid Paradise (€36.00) → `orchids.png`** [NEW]
10. ✨ **Hydrangea Elegance (€40.00) → `hydrangeas.png`** [NEW]

## Adding a 7th+ Flower Type (Optional)

If you want to add more flower types later:

1. Add a new PNG file to `/assets/images/flowers/` (e.g., `daisies.png`)
2. Edit `functions.php` and add a keyword check in `secret_flower_shop_get_fallback_flower_image()`:
   ```php
   } elseif ( false !== strpos( $text, 'daisy' ) || false !== strpos( $text, 'daisies' ) ) {
       $image_file = 'daisies.png';
   ```
3. Create posts with the new keyword in title/content

## Auto-Regeneration of Demo Products

Every time you update or reinstall the theme with a new version, the 10 demo products are automatically regenerated with fresh EUR prices (€10–€40). Old products are deleted and replaced. This ensures you always have the latest pricing and product lineup on a fresh install.

## Recommended Image Dimensions

- **Width × Height:** 260×260px (or larger for high-DPI displays)
- **Format:** PNG with transparency recommended
- **File size:** Keep under 500KB per image for best performance

## Troubleshooting

**Q: My image doesn't show up?**
- A: Check that the filename matches exactly (case-sensitive on some servers)
- A: Verify the flower keyword is in the post title or content
- A: Clear browser cache and reload

**Q: I want a featured image instead of fallback?**
- A: Upload a featured image in the WordPress post editor
- A: Featured images always take priority over fallback images

**Q: Can I use JPG instead of PNG?**
- A: Yes! Just update the filename in the code or use PNG
- A: PNG recommended for transparency/quality

## File Locations Reference

```
wordpress.project/
├── assets/
│   └── images/
│       └── flowers/
│           ├── .gitkeep
│           ├── roses.png
│           ├── tulips.png
│           ├── lilies.png
│           ├── sunflowers.png      ← NEW
│           ├── dahlias.png         ← NEW
│           └── peonies.png         ← NEW
├── functions.php                    (Keyword matching logic)
├── front-page.php                   (Home display)
├── page-shop.php                    (Shop display)
└── ...
```

---

**Ready to go!** Just replace these 6 PNG files with your own flower photos, and your storefront will display them automatically. 🌸
