# Custom Pages
## Create Custom Page to override CRUD Generator UI

To override CRUD Generator UI, create vue component like below
```
root_project
    | resources
        | js
            | badaso
                | custom_pages
                    | {slug}
                        | browse
                        | read
                        | edit
                        | add
                        | sort
```

You need to rebuild your app after adding custom pages