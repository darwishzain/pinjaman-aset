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
- one loan can only belong to one request
- one request may not have loan
- one request can have multiple loan
---
**T3-T5/T5-T3** 
`KIV for now.`
- one book can only belong to one request
- one request may not have book
- one request can only have one book
---
**T2-T4/T4-T2**
- loan need to have at least one asset
- loan can only have one asset
- asset may not have a loan at all
- asset can be in multiple loan

