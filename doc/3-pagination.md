# 3 - Pagination

[Pagerfanta](https://github.com/whiteoctober/Pagerfanta) is a well-known and
powerful PHP pager. Let's use it!

## 3.1 - WhiteOctoberPagerfantaBundle

**Task:** In order to use it, uncomment the line to enable the
[WhiteOctoberPagerfantaBundle](https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle)
in the `AppKernel` class.

## 3.2 - Introducing [Query|Request] Params

**Task:** By combining FOSRestBundle `@QueryParam` and the Pagerfanta, modify the
`allAction()` to provide a paginated collection.

![](screenshots/paginated-users-html.png)

## 3.3 - Retrieving a user

**Task:** Create a `getAction()` that returns a given user.

---

[Previous](2-the-apibundle.md)&nbsp;|&nbsp;[Back to the
index](https://github.com/willdurand/workshop-rest-from-zero-to-hero#instructions)
&nbsp;|&nbsp;[Next](4-testing.md)
