# Backend task:
Write a small application that queries GitHub's API and returns 500 repos with the topic "php" (https://developer.github.com/v3/search/#search-repositories). Your application should expose the following endpoint:

/api/repos - returns 500 repositories with the topic "php"
- should be able to search by repository name
- should be able sort the repositories by:
    - name
    - popularity (stargazers_count)
    - activity (updated_at)
- enable pagination

### Some notes:
Your responses should be in JSON format. Each repository in the response should only contain the fields id, name, full_name, html_url, language, updated_at, pushed_at and stargazers_count.
Don't use GitHub's API query params to sort the results. Instead, retrieve the default results and sort them yourself.
You must use Laravel as a framework.


# Frontend task (vue/react):
- create one page that shows the repository data from the api above using your favorite datatable library
- add search form to search repository by name (server side)
- enable pagination (server side)
- columns that should be sortable (server side):
    - name
    - popularity (stargazers_count)
    - activity (updated_at)

create a repo on github (could be mono repo or separate repo for backend and frontend), then push the code to the repo.
Please include instructions on how to run the application locally so that it can be tested on a *nix operating system.

# Getting Started with Laravel + Vue.js test project

Laravel v9.52.9(PHP 8.0.25) + Vue3 

## Available Scripts

In the project directory, you can run:

### Run Laravel backend

https://github.com/eriellebancolita017/laravel-vue-test/blob/a27122531d2b50fa02182b0ad67ae1412e9d615c/config/services.php#L35
Change the github access token with yours. (current one has revoked)

```
php artisan serve
```


### Run Vue3 front-end

install npm package

```
npm install
```

Then run front-end

```
npm run dev
```

Open [http://localhost:8000](http://localhost:8000) to view it in your browser.

## Action

There is a table to show 500 repositories with php topic. 
It has search on the top of table and sortable columns such as name, popularity (stargazers_count), activity (updated_at).
E.g. If you click the name, it'll sort by name ascending order and if you click again, then sort by descending order.

And there is a pagination at the bottom of table. It shows current page, 2 pages before, 2 pages after, start page, end page, previous button, next button. 

In the front-end, only send query with search, sort and pagination data and get the result from Laravel backend.

In laravel backend, at first receive 500 php repositories data from GitHub Search Repository API and directly search, sort and paginate data and return it to `/api/repos` api. (GitHub Search Repository API get only 100 results default so I combined them to 500)



