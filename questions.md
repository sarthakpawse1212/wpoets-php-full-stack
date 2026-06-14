# Answers to Technical Questions

## 1. How long did you spend on the coding test? What would you add to your solution if you had more time?

I spent approximately **6-8 hours** completing the coding test, including project setup, database design, CRUD implementation, frontend development, responsive behavior, testing, and documentation.

If I had more time, I would further enhance the solution by:

* Implementing AJAX-based CRUD operations to improve user experience by avoiding full page reloads.
* Adding server-side and client-side validation with more detailed error handling.
* Introducing pagination and search functionality for managing large datasets.
* Adding image optimization and lazy loading to improve performance.
* Writing automated tests for backend functionality.
* Adding role-based authentication for the admin panel.
* Containerizing the application using Docker for easier deployment and development consistency.

My primary focus during the assessment was to deliver a clean, maintainable, and fully functional solution that satisfies all requirements while following good development practices.

---

## 2. How would you track down a performance issue in production? Have you ever had to do this?

Yes, I have worked on production issues in enterprise applications where identifying performance bottlenecks was critical to maintaining service reliability.

My approach typically follows these steps:

### 1. Understand the Symptoms

First, I gather information about the issue:

* Is the application slow for all users or specific users?
* Is the issue consistent or intermittent?
* Which API, page, or service is affected?
* When did the issue start?

### 2. Analyze Monitoring Metrics

I review available monitoring data such as:

* CPU utilization
* Memory consumption
* Response times
* Error rates
* Throughput and request volume

These metrics help determine whether the bottleneck is application-related, infrastructure-related, or database-related.

### 3. Review Logs

I inspect application logs and server logs to identify:

* Slow requests
* Exceptions
* Timeouts
* External dependency failures

Structured logging often helps narrow down the affected component quickly.

### 4. Investigate Database Performance

Database queries are a common source of performance issues. I would:

* Identify slow-running queries
* Review execution plans
* Check indexing strategies
* Look for unnecessary joins or large result sets

### 5. Evaluate External Dependencies

If the application relies on third-party services, I verify:

* API response times
* Network latency
* Retry behavior
* Service availability

### 6. Analyze Caching

I check whether caching is functioning correctly and whether frequently accessed data can be served from cache instead of repeatedly querying databases or external systems.

### Real-World Experience

In my current role as a Software Engineer, I have worked on backend systems built with Node.js, TypeScript, Redis, Kafka, MongoDB, and Elasticsearch.

One common production issue involved increased response times caused by heavy database and service interactions during peak traffic periods. By analyzing logs, monitoring metrics, and request flows, we identified inefficient data retrieval patterns and optimized the affected components through query improvements and better cache utilization. This significantly reduced response times and improved overall system stability.

My general philosophy is to rely on data and monitoring rather than assumptions, isolating the bottleneck systematically before applying fixes.

---

## 3. Please describe yourself using JSON.

```json
{
  "name": "Sarthak Pawse",
  "role": "Software Engineer",
  "experience": "2.6+ years",
  "location": "India",
  "skills": [
    "Node.js",
    "TypeScript",
    "JavaScript",
    "React.js",
    "Next.js",
    "PHP",
    "MySQL",
    "MongoDB",
    "PostgreSQL",
    "Redis",
    "Kafka",
    "AWS"
  ],
  "expertise": [
    "Backend Development",
    "REST APIs",
    "Microservices",
    "Database Design",
    "Cloud Technologies"
  ],
  "currentlyLearning": [
    "AI Agents",
    "LLM Integrations",
    "System Design",
    "Distributed Systems"
  ],
  "strengths": [
    "Problem Solving",
    "Ownership",
    "Continuous Learning",
    "Debugging Complex Systems",
    "Collaboration"
  ],
  "careerGoal": "Build scalable software products and continuously improve as a full-stack engineer."
}
```
