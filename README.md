# JSON Data plugin for craftcms

Adds `jsonData` filter to templates allowing the pipe in of entry data and return of json encoded data.

It accepts field names as parameters (comma separated) and works with related fields.

Very early release.

```
// Template Tag
{{ entries | jsonData(
                "id",
                "title",
                "category.title",
                "myRelatedField.title",
                "myRelatedField.customField",
                "myRelatedField.itsRelatedField.title") | raw }}

// Passing an array of entries returns
{
    {
        "id": 1,
        "title": "Entry Title",
        "category": [
            {
                "title": "Category Title"
            }
        ],
        "myRelatedField": [
            {
                "title": "Related Entry's Title",
                "customField": "Custom Data",
                "itsRelatedField": [
                    {
                        "title": "Related Related Title"
                    }
                ],
            }
        ]
    },
    {
        "id": 2,
        "title": "Entry Title 2",
        "category": [
            {
                "title": "Category Title 2"
            }
        ],
        "myRelatedField": [
            {
                "title": "Related Entry's Title 2",
                "customField": "Custom Data 2",
                "itsRelatedField": [
                    {
                        "title": "Related Related Title 2"
                    }
                ],
            }
        ]
    },
}

// Passing a single entry returns
{
    "id": 1,
    "title": "Entry Title",
    "category": [
        {
            "title": "Category Title"
        }
    ],
    "myRelatedField": [
        {
            "title": "Related Entry's Title",
            "customField": "Custom Data",
            "itsRelatedField": [
                {
                    "title": "Related Related Title"
                }
            ],
        }
    ]
}
```

Added the ability to return Asset transforms by using getUrl() with the handle and returns it in the array

```
// Template Tag
{{ entries | jsonData(
                "id",
                "image.getUrl('myTransformHandle')") | raw }}



// Returns
{
    {
        "id": 1,
        "image":  [
            {
                "myTransformHandle": "http://example.craft.dev/upload/path/_myTransformHandle/image.jpg"
            }
        ]
    }
}
```

## JSON Data Changelog

### 0.1.2 -- 2014.05.19

* Added parsing for returning raw content of Rich Text Fields

### 0.1.1 -- 2015.08.01

* Allowed return of single entry

### 0.1.0 -- 2014.05.19

* Initial release

Brought to you by [nfourtythree](http://n43.me)
