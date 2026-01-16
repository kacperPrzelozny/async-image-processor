# Distributed Async Image Processor

### 📌 Project Overview
This project is a **distributed system for asynchronous image processing** developed for the Distributed and Parallel Processing (PRiR) course. The architecture follows a producer-consumer model, offloading computationally expensive image manipulation tasks to background workers. This setup ensures that the API remains responsive even under heavy processing loads.

**Core Functionalities:**
* **Dynamic Resizing:** Resize images to specific pixel dimensions.
* **WebP Optimization:** Convert standard formats (JPEG/PNG) to optimized WebP.
* **Watermarking:** Add custom text overlays to images using the FreeType library.
* **Status Tracking:** Monitor jobs through states: `PENDING`, `PROCESSING`, `COMPLETED`, or `FAILED`.

### 🏗️ Distributed Architecture
The system is composed of five decoupled services running in a Dockerized environment, communicating over a virtual bridge network:

1.  **Nginx:** Acts as the entry point (Reverse Proxy) for all API requests.
2.  **PHP-FPM (Producer):** Handles incoming HTTP requests, performs validation, and dispatches jobs to the Redis queue.
3.  **Redis (Broker):** The message broker that stores the queue of tasks to be processed.
4.  **PHP-Worker (Consumer):** Independent background processes that pull tasks from Redis and perform the actual image processing.
5.  **MySQL/MariaDB:** Persistent storage for image metadata and processing logs.

---

### 🚀 Installation & Setup

**Prerequisites:** Docker and Docker Compose installed.

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/kacperPrzelozny/async-image-processor
    cd async-image-processor
    ```

2.  **Environment Setup:**
    ```bash
    cp .env.example .env
    ```
    *Ensure `QUEUE_CONNECTION=redis` and `REDIS_HOST=redis` are set in your `.env`.*

3.  **Build and Start Containers:**
    ```bash
    docker compose up -d --build
    ```

4.  **Install Dependencies & Migrations:**
    ```bash
    docker compose exec php composer install
    docker compose exec php php artisan migrate
    docker compose exec php php artisan storage:link
    ```

5. **Add permissions to storage**
    ```bash
   chmod -R 777 storage/
   ```
---

### 🛠️ How to Test

#### 1. Upload an Image & Dispatch a Job (POST)
Send a `POST` request to the API.

* **URL:** `http://localhost:7000/api/images`
* **Method:** `POST`
* **Body (form-data):**
    * `image`: [Select a file]
    * `action`: `changeDimensions` | `convertToWebp` | `addWatermark`
    * `width`: `500` (required for changeDimensions)
    * `height`: `500` (required for changeDimensions)
    * `quality`: `50` (required for convertToWebp)
    * `watermark`: "Hello World" (required for addWatermark)

#### 2. Check Job Status (GET)
Retrieve the image to verify if the status has changed from `PENDING` to `COMPLETED`.

* **URL:** `http://localhost:7000/api/images/{id}`
* **Method:** `GET`

#### 3. Monitor Worker Logs
Watch the distributed worker processing the tasks in real-time:
```bash
docker compose logs -f worker
