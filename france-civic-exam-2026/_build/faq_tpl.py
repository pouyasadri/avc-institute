# helper to build the FAQ accordion with the exact markup used by the current drafts
def faq(heading, items):
    out = [f'<h2 class="h3 mt-5 mb-4 fw-bold text-center">{heading}</h2>', '<div id="faq-accordion" class="faq-accordion">']
    for i, (q, a) in enumerate(items):
        first = i == 0
        btn_cls = "accordion-button d-flex" if first else "accordion-button collapsed d-flex"
        exp = "true" if first else "false"
        col = "accordion-collapse collapse show" if first else "accordion-collapse collapse"
        out.append(f'''<div class="faq-item mb-4">
<div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden transition-all">
<h3 id="heading-{i}" class="accordion-header mb-0"><button class="{btn_cls} align-items-center justify-content-between w-100 text-start border-0 bg-white p-4" data-bs-toggle="collapse" data-bs-target="#collapse-{i}" aria-expanded="{exp}" aria-controls="collapse-{i}"> <span class="faq-question fw-bold">{q}</span> </button></h3>
<div id="collapse-{i}" class="{col}" aria-labelledby="heading-{i}" data-bs-parent="#faq-accordion">
<div class="accordion-body bg-light p-4 border-top">
<div class="faq-answer text-muted"><p>{a}</p></div>
</div>
</div>
</div>
</div>''')
    out.append('</div>')
    return "\n".join(out)

def cta(title, text, btn, href, btn2, href2):
    return f'''<div class="my-5 p-5 text-center rounded-5 shadow-sm text-white bg-primary">
<h3 class="h4 mb-3 fw-bold text-white">{title}</h3>
<p class="mb-4 text-white">{text}</p>
<div class="d-flex flex-wrap justify-content-center gap-3"><a class="btn btn-dark btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm" href="{href}">{btn}</a> <a class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold" href="{href2}">{btn2}</a></div>
</div>'''
