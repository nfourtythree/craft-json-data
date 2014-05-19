// Template Tag
{{ entries | examplePlugin(
                "id",
                "title",
                "category.title",
                "myRelatedField.title",
                "myRelatedField.customField",
                "myRelatedField.itsRelatedField.title") | raw }}



// Returns
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