#!/usr/bin/env python3
"""
همه‌ی مطالب theme/content/blog را بررسی می‌کند و index.json را می‌سازد.
اگر خطای جدی باشد، با کد ۱ خارج می‌شود و index.json نوشته نمی‌شود.

بررسی‌ها (چک‌لیست سئوی بیزینس پلن):
  - عنوان سئو ≤ ۶۰ کاراکتر، توضیح متا ۱۲۰ تا ۱۶۰ کاراکتر و شامل یک عدد
  - کلیدواژه‌ی اصلی در عنوان، توضیح متا و پاراگراف اول
  - حداقل ۳ لینک داخلی، حداقل ۳ تیتر h2، حداقل ۸۰۰ کلمه
  - عکس با alt، سؤال‌های رایج، نامک لاتین
  - واژه‌های ممنوع برند: بهینه‌سازی، پلتفرم، راهکار جامع، هوشمند
"""
import json, os, re, sys, glob

ROOT = os.path.join(os.path.dirname(__file__), '..', 'theme', 'content', 'blog')
CATS = {'cake-pricing', 'home-food', 'cost-breakdown', 'ingredients', 'home-business'}
BANNED = ['بهینه‌سازی', 'بهینه سازی', 'پلتفرم', 'راهکار جامع', 'هوشمند']


def words(html):
    return len(re.sub(r'<[^>]+>', ' ', html).split())


def norm(s):
    return s.replace('‌', ' ').replace('ي', 'ی').replace('ك', 'ک')


def check(rel, meta, html):
    err, warn = [], []
    slug = meta.get('slug', '')
    if not re.fullmatch(r'[a-z0-9-]+', slug) or not rel.endswith('/' + slug):
        err.append('نامک با نام پوشه یکی نیست یا لاتین نیست')
    if meta.get('category') not in CATS or not rel.startswith(meta.get('category', '') + '/'):
        err.append('سرفصل نامعتبر یا با پوشه یکی نیست')
    t, d, kw = meta.get('seo_title', ''), meta.get('meta_description', ''), norm(meta.get('focus_keyword', ''))
    if not t or len(t) > 60:
        err.append(f'عنوان سئو {len(t)} کاراکتر (حداکثر ۶۰)')
    if not (110 <= len(d) <= 165):
        err.append(f'توضیح متا {len(d)} کاراکتر (۱۲۰ تا ۱۶۰)')
    if not re.search(r'[0-9۰-۹]', d):
        warn.append('توضیح متا عدد ندارد')
    if not kw:
        err.append('کلیدواژه‌ی اصلی ندارد')
    else:
        first_p = re.search(r'<p>(.*?)</p>', html, re.S)
        for name, txt in (('عنوان سئو', t), ('توضیح متا', d), ('عنوان', meta.get('title', '')), ('پاراگراف اول', first_p.group(1) if first_p else '')):
            if kw not in norm(txt):
                warn.append(f'کلیدواژه در {name} نیست')
    internal = re.findall(r'href="(#sp-link-[a-z0-9-]+|/[^"]*)"', html)
    if len(internal) < 3:
        err.append(f'{len(internal)} لینک داخلی (حداقل ۳)')
    if len(re.findall(r'<h2', html)) < 3:
        err.append('کمتر از ۳ تیتر h2')
    if '<h1' in html:
        err.append('متن نباید h1 داشته باشد')
    wc = words(html)
    if wc < 800:
        warn.append(f'{wc} کلمه (کمتر از ۸۰۰)')
    img = meta.get('image', {})
    if not img.get('file') or not img.get('alt'):
        err.append('عکس یا alt ندارد')
    elif not os.path.exists(os.path.join(ROOT, rel, img['file'])):
        err.append('فایل عکس پیدا نشد')
    if len(meta.get('faq', [])) < 3:
        warn.append('کمتر از ۳ سؤال رایج')
    for b in BANNED:
        if b in html or b in meta.get('title', ''):
            err.append(f'واژه‌ی ممنوع برند: {b}')
    return err, warn, wc


def main():
    posts, bad = [], False
    for mf in sorted(glob.glob(os.path.join(ROOT, '*', '*', 'meta.json'))):
        d = os.path.dirname(mf)
        rel = os.path.relpath(d, ROOT).replace(os.sep, '/')
        meta = json.load(open(mf, encoding='utf-8'))
        html = open(os.path.join(d, 'post.html'), encoding='utf-8').read()
        err, warn, wc = check(rel, meta, html)
        status = 'خطا' if err else 'OK'
        print(f'[{status}] {rel} ({wc} کلمه, {meta.get("publish_at","")})')
        for e in err:
            print('   ✗', e)
        for w in warn:
            print('   !', w)
        if err:
            bad = True
        posts.append((meta.get('publish_at', ''), rel))
    if bad:
        print('index.json نوشته نشد؛ اول خطاها را درست کن.')
        sys.exit(1)
    posts.sort()
    import hashlib
    h = hashlib.md5()
    for _, rel in posts:
        for fn in sorted(os.listdir(os.path.join(ROOT, rel))):
            h.update(fn.encode()); h.update(open(os.path.join(ROOT, rel, fn), 'rb').read())
    json.dump({'version': h.hexdigest(), 'posts': [p[1] for p in posts]}, open(os.path.join(ROOT, 'index.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
    print(f'index.json: {len(posts)} مطلب')


if __name__ == '__main__':
    main()
