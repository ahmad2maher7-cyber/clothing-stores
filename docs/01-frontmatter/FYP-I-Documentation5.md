## 3.4 Sequence Diagrams

### Sequence: Checkout

```mermaid
sequenceDiagram
    Customer->>Controller: POST /orders
    Controller->>Service: createOrder()
    Service->>Database: BEGIN TRANSACTION
    Service->>Database: Check Stock (FOR UPDATE)
    Service->>Database: INSERT Order
    Service->>Database: COMMIT
    Controller-->>Customer: Success Page