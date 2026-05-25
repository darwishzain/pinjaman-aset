# Peminjaman Alatan Komputer Bahagian Digital PKINK
### Database
- [Structure ](form/s21052026.sql) (`.sql`)
- [Sample User](form/T1_user_21052026.sql) (`.sql`)
## TODO
- [x] login(important - hardcoded the password - delete later) - maybe on `index.php`
- [ ] validate date not on weekends and datetouse > datetoreceive/datestart,dateend(dateend>datestart)
- [ ] fix UI
- [ ] better request id generation(?)
- [ ] documentation on T*_details syntax
- [ ] asset.details dynamic to datatype(str,int etc)

## Feature suggestions
- Close request option if no asset available
- Warning if device availablity is low
## Halaman (Status)
- Pengguna (Peminjam) `staff`
  - [x] peminjaman(baru)`/?request`
  - [ ] peminjaman(perihal)`/?request=REQUESTID` - only check status??
  - [ ] peminjaman(rekod)`/?list`
- Pengurus `manager`
  - [ ] peminjaman(pengesahan) `?request=REQUESTID`
  - [ ] peminjaman(rekod) `?request`
  - [x] peminjaman(--pending approval) `?`
- Pengendali `handler`
  - [x] aset(tambah)`/?asset&new`
  - [x] aset(kemaskini)`/?asset=ASSETID`
  - [x] aset(senarai)`/?asset`
  - [ ] peminjaman(senarai)`/?status=STATUS`
  - [x] peminjaman(record)`/?request`
  - [ ] peminjaman(pengesahan+tetapkan aset)`/?request=REQUESTID`

## Contoh Halaman
![](image/handler@addasset.png)
![](image/handler@listasset.png)
![](image/handler@request-assignapprove.png)
![](image/handler@request-pending.png)
![](image/manager@request-approve.png)
![](image/manager@request-pending.png)
![](image/staff@newrequest.png)
![](image/staff@request-all.png)
## Entity Relation Diagram (ERD)
![](image/erd.png)
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

