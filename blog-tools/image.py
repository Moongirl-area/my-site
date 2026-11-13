#!/usr/bin/env python3
"""
عکس جمنای را برای یک مطلب آماده می‌کند:
  - برش به نسبت 16:9 (از وسط)
  - چند اندازه webp فشرده بدون متادیتا: 1376، 960، 640، 400 پیکسل
  - نام فایل‌ها = نامک مطلب (سئو)
  - بلوک image داخل meta.json را به‌روز می‌کند (alt/title/caption موجود حفظ می‌شود)

استفاده:
  python3 blog-tools/image.py SRC.jpg theme/content/blog/<cat>/<slug>
  python3 blog-tools/image.py SRC.jpg --hero theme/assets/img/blog-hero   (عکس صفحه‌ی مجله)
"""
import json, os, sys
from PIL import Image, ImageOps

WIDTHS = [1376, 960, 640, 400]
RATIO = 16 / 9
MAX_KB_FULL = 220  # سقف حجم نسخه‌ی بزرگ


def crop_ratio(im, ratio=RATIO):
    w, h = im.size
    if abs(w / h - ratio) < 0.01:
        return im
    if w / h > ratio:
        nw = int(h * ratio)
        x = (w - nw) // 2
        return im.crop((x, 0, x + nw, h))
    nh = int(w / ratio)
    y = (h - nh) // 2
    return im.crop((0, y, w, y + nh))


def save_webp(im, path, width, max_kb=None):
    w, h = im.size
    if width < w:
        im = im.resize((width, round(h * width / w)), Image.LANCZOS)
    q = 80
    while True:
        im.save(path, 'WEBP', quality=q, method=6)
        kb = os.path.getsize(path) / 1024
        if not max_kb or kb <= max_kb or q <= 60:
            break
        q -= 5
    return im.size, round(kb), q


def main():
    src = sys.argv[1]
    im = ImageOps.exif_transpose(Image.open(src)).convert('RGB')
    im = crop_ratio(im)

    if sys.argv[2] == '--hero':
        base = sys.argv[3]
        (w, h), kb, q = save_webp(im, base + '.webp', 1376, 200)
        print(json.dumps({'file': base + '.webp', 'width': w, 'height': h, 'kb': kb, 'q': q}))
        return

    post_dir = sys.argv[2].rstrip('/')
    slug = os.path.basename(post_dir)
    os.makedirs(post_dir, exist_ok=True)
    # پاک کردن نسخه‌های قبلی همین مطلب
    for f in os.listdir(post_dir):
        if f.endswith('.webp'):
            os.remove(os.path.join(post_dir, f))

    sizes, full = [], None
    for i, width in enumerate(WIDTHS):
        if width > im.size[0] and i > 0:
            continue
        name = f'{slug}.webp' if i == 0 else f'{slug}-{width}.webp'
        (w, h), kb, q = save_webp(im, os.path.join(post_dir, name), width, MAX_KB_FULL if i == 0 else None)
        rec = {'file': name, 'width': w, 'height': h}
        print(f'{name}: {w}x{h} {kb}KB q{q}', file=sys.stderr)
        if i == 0:
            full = rec
        else:
            sizes.append(rec)

    meta_path = os.path.join(post_dir, 'meta.json')
    meta = json.load(open(meta_path, encoding='utf-8')) if os.path.exists(meta_path) else {}
    img = meta.get('image', {})
    img.update(full)
    img['sizes'] = sizes
    meta['image'] = img
    json.dump(meta, open(meta_path, 'w', encoding='utf-8'), ensure_ascii=False, indent=2)
    print(json.dumps(img, ensure_ascii=False))


if __name__ == '__main__':
    main()
