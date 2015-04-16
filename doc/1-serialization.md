# 1 - Serialization

Serialization is the process of transforming a graph of objects into a structured data format (XML, YAML, JSON but also binary).

## 1.1 - JMSSerializerBundle

Serialization is brought to you by the
[JMSSerializerBundle](http://jmsyst.com/bundles/JMSSerializerBundle) bundle, for free
;-)

**Task:** Start by modifying the `DefaultController` class to use the
`jms_serializer` service and return either some XML, HTML, or JSON.

> **Tip:** If there is one thing to retain, it is that serialization takes the exact same data set as input, but outputs it in different formats.

![](screenshots/1-serialization-json.png)

![](screenshots/1-serialization-xml.png)

## 1.2 - JMSSerializerBundle + FOSRestBundle = &hearts;

A small [Behat](http://docs.behat.org) test suite is provided:

    $ bin/behat features/basic-serialization.feature

> **Tip:** Always cover your code by test. If you don't feel good enough with unit testing, functional testing might be an option as it is often easier to understand.

The [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)
integrates with the JMSSerializerBundle, and provides the same feature throught
the concept of
"[views](http://symfony.com/doc/master/bundles/FOSRestBundle/2-the-view-layer.html)".

**Task:** Configure the FOSRestBundle to leverage [the View
layer](http://symfony.com/doc/master/bundles/FOSRestBundle/2-the-view-layer.html).

> **Tip:** FOSRestBundle leverages the JMSSerializerBundle for the "serialization" part. If you only use this feature, no need to use the FOSRestBundle. However, this bundle provides many interesting features that we are going to cover in the following.

## 1.3 - Leveraging JMSSerializerBundle

**Task:** Configure the XML output to get the following document:

```xml
<hello>
    <name><![CDATA[ will ]]></name>
</hello>
```

> **Tip:** Your response does not have to be the mirror of your database schema. When building an API, you should think about the response first, and then about how to store data.

![](screenshots/3-serialization-xml.png)

---

[Back to the index](https://github.com/willdurand/workshop-rest-from-zero-to-hero#instructions)
&nbsp;|&nbsp;[Next](2-the-apibundle.md)
