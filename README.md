# sbx-demo

A small Symfony notes API that runs in Docker. It is the companion repo for the Not Vibe Coding video on running a
coding agent with full permissions inside Docker Sandboxes.

```sh
docker compose up -d
curl localhost:8000/api/health
curl localhost:8000/api/notes
curl -X POST localhost:8000/api/notes \
  -H "Authorization: Bearer $API_TOKEN" -H 'Content-Type: application/json' \
  -d '{"text":"first note"}'
```

| Endpoint | What it does |
|---|---|
| `GET /api/health` | `{"status":"ok"}` |
| `GET /api/notes` | every note |
| `POST /api/notes` | adds a note; needs `Authorization: Bearer <API_TOKEN>` |

Writes need `API_TOKEN`. Put it in `.env.local`, which git ignores; `.env` only declares it. Notes are kept in
`var/notes.json`.

Symfony 8, PHP 8.4, FrankenPHP.
