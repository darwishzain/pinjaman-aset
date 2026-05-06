# item-rental
## Entity Relation Diagram (ERD)
![](erd.png)
### Justification

**T1-T3/T3-T1**
- user may not make a request
- user can make multiple request
- one request can only belong to one user
---
**T1-T2/T2-T1**
- user may not handle an asset
- user can hanfle many assets
- one asset can only be handled by one user
---
**T3-T4/T4-T3**
- one rent can only belong to one request
- one request may not have rent
- one request can only have one rent
---
**T3-T5/T5-T3**
- one book can only belong to one request
- one request may not have book
- one request can only have one book
---
**T2-T4/T4-T2**
- rent need to have at least one asset
- rent can have multiple asset
- asset may not have a rent at all
- asset can be in multiple rent

