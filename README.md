# Popular-Posts-API

### 1. Get Popular Posts

- **Endpoint:** `GET /wp-json/custom/v1/popular`
- **Returns:** An array of the 8 most popular posts, including:
  - Post ID
  - Title
  - Excerpt
  - Permalink
  - Total upvotes

### 2. Upvote a Post

- **Endpoint:** `POST /wp-json/custom/v1/upvote`
- **Body Parameters:**
  ```json
  {
    "post_id": 123
  }