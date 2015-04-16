# 9 - Hateoas

HATEOAS stands for Hypermedia as the Engine of Application State, and adds
hypermedia links to your representations (i.e. your API responses). [HATEOAS is
about the discoverability of actions on a
resource](http://timelessrepo.com/haters-gonna-hateoas). This is a
**requirement** for building a REST API.

The [Hateoas](https://github.com/willdurand/Hateoas) PHP library leverages the
(JMS) Serializer library to provide a nice way to build HATEOAS REST web
services.

**Task:**  Transform the `allAction()` response in a
[HAL](http://stateless.co/hal_specification.html) compliant response:

```json
{
    "_embedded": {
        "users": [
            ...
        ]
    },
    "_links": {
        ...
    },
    "limit": 10,
    "page": 1,
    "pages": 7,
    "total": 66
}
```

> **Tip:** Hateoas has built-in support for **Pagerfanta**.

**Task:** Write scenarios to cover this new feature.

---

[Previous](8-documentation.md)&nbsp;|&nbsp;[Back to the
index](https://github.com/willdurand/workshop-rest-from-zero-to-hero#instructions)
&nbsp;|&nbsp;[Next](10-security.md)
