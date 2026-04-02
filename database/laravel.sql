--
-- PostgreSQL database dump
--

\restrict qww8LqFWxf5BcmYseh0Rn4ym8E3hcco9aQe2MbTEdyjFCB1zu3ks6rBRKTV0pno

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: bahans; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bahans (
    id bigint NOT NULL,
    nama_outlet character varying(255) NOT NULL,
    tepung_roti numeric(10,2),
    tepung_bumbu numeric(10,2),
    garam numeric(10,2),
    bubuk_cabe numeric(10,2),
    telur numeric(10,2),
    gula numeric(10,2),
    ayam numeric(10,2),
    tepung numeric(10,2),
    teh numeric(10,2),
    beras numeric(10,2),
    cup numeric(10,2),
    kertas_chicken_kecil numeric(10,2),
    kertas_chicken_sedang numeric(10,2),
    kertas_chicken_besar numeric(10,2),
    dus_chicken numeric(10,2),
    dus_chicken_jumbo numeric(10,2),
    plastik_cup_isi_1 numeric(10,2),
    plastik_cup_isi_2 numeric(10,2),
    plastik_ayam_kecil numeric(10,2),
    plastik_sedang numeric(10,2),
    plastik_tanggung numeric(10,2),
    plastik_besar numeric(10,2),
    plastik_jumbo numeric(10,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.bahans OWNER TO postgres;

--
-- Name: bahans_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bahans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.bahans_id_seq OWNER TO postgres;

--
-- Name: bahans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bahans_id_seq OWNED BY public.bahans.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: marinasi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marinasi (
    id bigint NOT NULL,
    daging_ayam integer DEFAULT 0 NOT NULL,
    saus_teriyaki integer DEFAULT 0 NOT NULL,
    bawang_putih integer DEFAULT 0 NOT NULL,
    lada integer DEFAULT 0 NOT NULL,
    garam integer DEFAULT 0 NOT NULL,
    ketumbar integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kode_batch character varying(255) NOT NULL
);


ALTER TABLE public.marinasi OWNER TO postgres;

--
-- Name: marinasi_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marinasi_batches (
    id bigint NOT NULL,
    kode_batch character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marinasi_batches OWNER TO postgres;

--
-- Name: marinasi_batches_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marinasi_batches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marinasi_batches_id_seq OWNER TO postgres;

--
-- Name: marinasi_batches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marinasi_batches_id_seq OWNED BY public.marinasi_batches.id;


--
-- Name: marinasi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marinasi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marinasi_id_seq OWNER TO postgres;

--
-- Name: marinasi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marinasi_id_seq OWNED BY public.marinasi.id;


--
-- Name: marinasi_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marinasi_items (
    id bigint NOT NULL,
    marinasi_id bigint NOT NULL,
    bahan character varying(50) CONSTRAINT marinasi_items_nama_bahan_not_null NOT NULL,
    jenis character varying(50),
    total numeric(10,2) DEFAULT 0 NOT NULL,
    penggunaan character varying(50),
    banyak numeric(10,2),
    satuan character varying(20) DEFAULT 'gr'::character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE public.marinasi_items OWNER TO postgres;

--
-- Name: marinasi_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marinasi_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marinasi_items_id_seq OWNER TO postgres;

--
-- Name: marinasi_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marinasi_items_id_seq OWNED BY public.marinasi_items.id;


--
-- Name: marinasis; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marinasis (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marinasis OWNER TO postgres;

--
-- Name: marinasis_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marinasis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marinasis_id_seq OWNER TO postgres;

--
-- Name: marinasis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marinasis_id_seq OWNED BY public.marinasis.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'outlet'::character varying NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'SPV'::character varying, 'outlet'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: bahans id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bahans ALTER COLUMN id SET DEFAULT nextval('public.bahans_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: marinasi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi ALTER COLUMN id SET DEFAULT nextval('public.marinasi_id_seq'::regclass);


--
-- Name: marinasi_batches id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_batches ALTER COLUMN id SET DEFAULT nextval('public.marinasi_batches_id_seq'::regclass);


--
-- Name: marinasi_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_items ALTER COLUMN id SET DEFAULT nextval('public.marinasi_items_id_seq'::regclass);


--
-- Name: marinasis id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasis ALTER COLUMN id SET DEFAULT nextval('public.marinasis_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: bahans; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bahans (id, nama_outlet, tepung_roti, tepung_bumbu, garam, bubuk_cabe, telur, gula, ayam, tepung, teh, beras, cup, kertas_chicken_kecil, kertas_chicken_sedang, kertas_chicken_besar, dus_chicken, dus_chicken_jumbo, plastik_cup_isi_1, plastik_cup_isi_2, plastik_ayam_kecil, plastik_sedang, plastik_tanggung, plastik_besar, plastik_jumbo, created_at, updated_at) FROM stdin;
2	Outlet 2	10.00	8.00	1.00	1.00	25.00	2.00	20.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 08:38:33	2025-12-17 08:38:33
3	Outlet 3	16.00	9.00	2.00	1.00	35.00	3.00	30.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 08:38:33	2025-12-17 08:38:33
4	Outlet 4	10.00	7.00	1.00	1.00	22.00	2.00	18.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 08:38:33	2025-12-17 08:38:33
5	Outlet 5	13.00	8.00	1.00	1.00	28.00	3.00	27.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 08:38:33	2025-12-17 08:38:33
31	Outlet 1	1013.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 01:51:40	2025-12-17 01:51:40
32	Outlet 1	1000.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 02:31:34	2025-12-17 02:31:34
33	Outlet 1	1123.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 02:31:41	2025-12-17 02:31:41
34	Outlet 2	7.00	20.00	24.00	1.00	25.00	2.00	20.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 02:48:52	2025-12-17 02:48:52
35	Outlet 3	1271.00	421.00	54.00	164.00	390.00	646.00	551.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 09:52:20	2025-12-17 09:52:20
36	Outlet 1	1124.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 10:21:29	2025-12-17 10:21:29
37	Outlet 1	124.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 14:47:45	2025-12-17 14:47:45
38	Outlet 1	134.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 14:48:43	2025-12-17 14:48:43
39	Outlet 1	34.00	8.00	-1.00	0.00	20.00	-2.00	-75.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-18 07:29:16	2025-12-18 07:29:16
40	Outlet 1	-66.00	8.00	-1.00	0.00	20.00	-2.00	-175.00	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-18 07:30:12	2025-12-18 07:30:12
72	Outlet 7	18.00	14.50	4.00	2.50	40.00	10.00	32.00	20.00	8.00	28.00	140.00	260.00	190.00	130.00	100.00	55.00	360.00	300.00	220.00	200.00	180.00	150.00	110.00	2026-01-31 06:24:42	2026-01-31 06:24:42
73	Outlet 8	22.00	17.00	5.50	3.00	50.00	12.50	40.00	25.00	10.00	35.00	180.00	320.00	240.00	180.00	130.00	75.00	450.00	380.00	300.00	260.00	230.00	200.00	160.00	2026-01-31 06:24:42	2026-01-31 06:24:42
71	Outlet 6	12.50	10.00	3.25	1.75	30.00	8.50	25.00	15.00	6.00	20.00	100.00	200.00	191.00	100.00	80.00	40.00	300.00	250.00	180.00	160.00	140.00	120.00	90.00	2026-01-31 06:24:42	2026-02-16 19:48:46
1	Outlet 1	55.00	9.00	1.00	1.00	30.00	3.00	25.00	\N	\N	\N	\N	\N	\N	\N	62.00	\N	\N	\N	\N	\N	\N	\N	\N	2025-12-17 08:38:33	2026-02-16 19:49:28
74	Outlet 2	-117.00	20.00	24.00	1.00	25.00	2.00	20.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	2026-02-16 19:57:03	2026-02-16 19:57:03
75	Outlet 1	-66.00	8.00	-1.00	0.00	20.00	-2.00	-175.00	0.00	0.00	0.00	0.00	100.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	2026-03-04 23:01:23	2026-03-04 23:01:23
76	Outlet 1	-66.00	8.00	-1.00	0.00	20.00	-2.00	-175.00	0.00	0.00	0.00	0.00	50.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	2026-03-04 23:02:36	2026-03-04 23:02:36
77	Outlet 1	-65.00	8.00	-1.00	0.00	20.00	-2.00	-175.00	0.00	0.00	0.00	0.00	50.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	2026-03-04 23:06:45	2026-03-04 23:06:45
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-outlet6@gmail.com|125.166.36.35:timer	i:1771138354;	1771138354
laravel-cache-outlet6@gmail.com|125.166.36.35	i:1;	1771138354
laravel-cache-admin@gmail.com|125.166.36.35:timer	i:1771138364;	1771138364
laravel-cache-admin@gmail.com|125.166.36.35	i:1;	1771138364
laravel-cache-admin@gmail|180.247.59.146:timer	i:1772636140;	1772636140
laravel-cache-admin@gmail|180.247.59.146	i:1;	1772636141
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: marinasi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasi (id, daging_ayam, saus_teriyaki, bawang_putih, lada, garam, ketumbar, created_at, updated_at, kode_batch) FROM stdin;
\.


--
-- Data for Name: marinasi_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasi_batches (id, kode_batch, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: marinasi_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasi_items (id, marinasi_id, bahan, jenis, total, penggunaan, banyak, satuan, created_at, updated_at) FROM stdin;
1	230702	Marinasi A	Bumbu Tepung Marinasi	1989.00	Tambah Stok	1989.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
2	230702	Marinasi B	Bumbu Tepung Marinasi	5895.00	Tambah Stok	5895.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
3	230702	Marinasi & Lapis C	Bumbu Tepung Marinasi	735.00	Tambah Stok	735.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
4	230702	Marinasi D	Bumbu Tepung Marinasi	673.00	Tambah Stok	673.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
5	230702	Marinasi E	Bumbu Tepung Marinasi	1368.00	Tambah Stok	1368.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
6	230702	Marinasi F	Bumbu Tepung Marinasi	68.00	Tambah Stok	68.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
7	230702	Marinasi G	Bumbu Tepung Marinasi	96.00	Tambah Stok	96.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
8	230702	Marinasi H	Bumbu Tepung Marinasi	57.00	Tambah Stok	57.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
9	230702	Marinasi I	Bumbu Tepung Marinasi	934.00	Tambah Stok	934.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
10	230702	Marinasi & Lapis J	Bumbu Tepung Marinasi	572.00	Tambah Stok	572.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
11	230702	Marinasi K	Bumbu Tepung Marinasi	90.00	Tambah Stok	90.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
12	230702	Marinasi & Lapis L	Bumbu Tepung Marinasi	126.00	Tambah Stok	126.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
13	230702	Marinasi & Lapis M	Bumbu Tepung Marinasi	742.00	Tambah Stok	742.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
14	230702	Marinasi & Lapis N	Bumbu Tepung Marinasi	965.00	Tambah Stok	965.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
15	230702	Marinasi O	Bumbu Tepung Marinasi	257.00	Tambah Stok	257.00	Gram	2026-03-06 14:39:56	2026-03-06 14:39:56
16	280820	Marinasi E	Bumbu Tepung Marinasi	51.00	Tambah Stok	51.00	Gram	2026-03-06 14:43:32	2026-03-06 14:43:32
17	850742	Marinasi A	Bumbu Tepung Marinasi	-1000.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
18	850742	Marinasi B	Bumbu Tepung Marinasi	-2.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
19	850742	Marinasi & Lapis C	Bumbu Tepung Marinasi	-10.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
20	850742	Marinasi D	Bumbu Tepung Marinasi	-10.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
21	850742	Marinasi E	Bumbu Tepung Marinasi	-10.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
22	850742	Marinasi F	Bumbu Tepung Marinasi	-10.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
23	850742	Marinasi G	Bumbu Tepung Marinasi	-10.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
24	850742	Marinasi H	Bumbu Tepung Marinasi	-10.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
25	850742	Marinasi I	Bumbu Tepung Marinasi	-12.50	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
26	850742	Marinasi & Lapis J	Bumbu Tepung Marinasi	-30.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
27	850742	Marinasi K	Bumbu Tepung Marinasi	-600.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
28	850742	Marinasi & Lapis L	Bumbu Tepung Marinasi	-200.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
29	850742	Marinasi & Lapis M	Bumbu Tepung Marinasi	-100.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
30	850742	Marinasi & Lapis N	Bumbu Tepung Marinasi	-700.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
31	850742	Marinasi O	Bumbu Tepung Marinasi	-100.00	Ayam	1500.00	Pieces	2026-03-06 14:43:45	2026-03-06 14:43:45
32	917492	Lapis A	Bumbu Tepung Lapis	-14140.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
33	917492	Marinasi & Lapis C	Bumbu Tepung Lapis	-140.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
34	917492	Marinasi & Lapis J	Bumbu Tepung Lapis	-300.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
35	917492	Marinasi & Lapis L	Bumbu Tepung Lapis	-10000.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
36	917492	Marinasi & Lapis M	Bumbu Tepung Lapis	-5600.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
37	917492	Marinasi & Lapis N	Bumbu Tepung Lapis	-7300.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
38	917492	Lapis P	Bumbu Tepung Lapis	-1000.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
39	917492	Lapis Q	Bumbu Tepung Lapis	-1600.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
40	917492	Lapis R	Bumbu Tepung Lapis	-3200.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
41	917492	Lapis S	Bumbu Tepung Lapis	-2000.00	Simpan Karung	50.00	Kilogram	2026-03-06 14:43:56	2026-03-06 14:43:56
\.


--
-- Data for Name: marinasis; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasis (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_10_03_114558_create_marinasi_table	1
5	2025_10_03_115107_create_marinasis_table	1
6	2025_12_18_000000_create_marinasi_batches_table	1
7	2025_12_19_011558_create_marinasi_batch_items_table	1
8	2025_12_19_013224_add_kode_batch_to_marinasi_table	1
9	2026_02_15_131310_create_bahans_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
41BvbNcEvkRRyJFaOJKGLrI2nP8nA431ITP83ioU	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36	YToyOntzOjY6Il90b2tlbiI7czo0MDoiVG80MnlOV0h6SUZFbWl1OGFnNVI1TDNPd0hMcXBvR2VPWXMwelg3RSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1765809042
50hbOzADZdEsKp78qz0xMeLz8fb0ZE7d4A5mjkBO	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiemNUQ2ZvTURPanpQS3M2ZUpyenhqejR1dDhGRmtiajZqYllVZGJxRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO319	1765808929
5MccgrRUi6ChebPck4bIklHjuckxdAztzeV27iGW	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoibk1Qc3ZxYzBsSENqTzRaYXFGcklQNmdSVzdNWnhsQ2xJSmFQejF0ZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1765808578
ByqUT1wMDboIEhrwlygfiqoqVIr2MzzpKVAcRM8y	38	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRGZybE1OUU1KYVJuTmgyb1dNdEFHdVRLdGt1djEzUWticjVaZUZwTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozODt9	1765803374
fJeTefi8YVsi7NcyUxCfuc2wkDflkGjzjF6CTCTp	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFJqSWxkV0VrMExMVkM0QTRYTzdiWGcza1JhN0VsMG4zRExVM25jNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO319	1765809176
KxpFAhnOAdX4mXEF66yMBZXyEZxYmCkoacTIFmhG	\N	127.0.0.1	Mozilla/5.0 (Windows NT 6.1; WOW64; rv:59.0) Gecko/20100101 Firefox/59.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWpmQmRqeVFheUx2a3ladmtVTkJjWXJIWjNFTG0xbndHbHo4dXdLdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1765808605
LNWqpZaockhEL9bcMFkAQl4x4vH0GAdTZGU6XGaf	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36	YToyOntzOjY6Il90b2tlbiI7czo0MDoiRkY0czZubkFucXZueTNmU2F2RUlIVHlKUU9aQ2lrcHNXdGNzUm1nZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1765809188
Nmumta31zHnTsOBlvswka8I12z4PlAKzcY923ysv	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTJaalFmNmEweHJjbG9RUmY2ajhFQUVOc2NKcmtwOWdwT2k4Zm1VdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1765803636
nZYyWW7P86VvXUl7pe5ugOR53g4SNEMAeq010F1E	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3o3RWVGUExtN2lRUXVuaG9aSTY2Z1JNWXVyUUttTmN1Q2hzdjU3ayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO319	1765808604
oc8gaLwESTqm5gxPOmFfV90k4ofhWgc84BqSimaw	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoieGdGR3FPdEJaRkEzNlduQW1lM01HY05pcVZKRkRTTzVNUHoxOTdZUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1765803540
OVU9zz8En6Qnme9az5zEQA8s1GoQGPOAZ5cJ61Bf	\N	127.0.0.1	Mozilla/5.0 (Windows NT 6.1; WOW64; rv:59.0) Gecko/20100101 Firefox/59.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGtId0xQM2hpM1JhQjJ3RzFIelNGaFZXWGhtWlM2bFVNUEZNMkhRUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1765808631
tJJ75evX2vJW0SDa36mQaxPfZ572Czl8NiSLTgQI	30	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.1 Safari/605.1.15	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVmNpazVuTU5FVjN3UFZzRnVTSHZHZGo5THE2TFluZkZyT0VQRnd0aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozMDt9	1765713732
yjJzZdRLOE4CbKkCSHWSf7luwES7qdYjxl7oQQ1W	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXJQWVkweFR1am15MkZQR0FmOFl0UmxmeUc4bVo1RTdYVEtKYTFZViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9taXNzLXNraWxsZWQtY29tcHV0ZS1hdXN0aW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1765803674
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) FROM stdin;
1	Supervisor	spv@example.com	\N	$2y$12$O1wF3N8f8pX4o9zYpP0nFexJ8kQeJzK7vG8n0Qy0QpZl2K9ZxUe9a	SPV	\N	2025-10-03 17:25:20	2025-10-03 17:25:20
2	Outlet 1	outlet1@example.com	\N	$2y$12$O1wF3N8f8pX4o9zYpP0nFexJ8kQeJzK7vG8n0Qy0QpZl2K9ZxUe9a	outlet	\N	2025-10-03 17:25:21	2025-10-03 17:25:21
3	Outlet 2	outlet2@example.com	\N	$2y$12$O1wF3N8f8pX4o9zYpP0nFexJ8kQeJzK7vG8n0Qy0QpZl2K9ZxUe9a	outlet	\N	2025-10-03 17:25:21	2025-10-03 17:25:21
4	Outlet 3	outlet3@example.com	\N	$2y$12$O1wF3N8f8pX4o9zYpP0nFexJ8kQeJzK7vG8n0Qy0QpZl2K9ZxUe9a	outlet	\N	2025-10-03 17:25:21	2025-10-03 17:25:21
5	Outlet 4	outlet4@example.com	\N	$2y$12$O1wF3N8f8pX4o9zYpP0nFexJ8kQeJzK7vG8n0Qy0QpZl2K9ZxUe9a	outlet	\N	2025-10-03 17:25:22	2025-10-03 17:25:22
6	Outlet 5	outlet5@example.com	\N	$2y$12$O1wF3N8f8pX4o9zYpP0nFexJ8kQeJzK7vG8n0Qy0QpZl2K9ZxUe9a	outlet	\N	2025-10-03 17:25:22	2025-10-03 17:25:22
7	Admin	admin@gmail.com	\N	$2y$12$JX1uMeWyzOP9zG34/sxFuuJKAiHTca7B02LZ.Pe2Am.s9IVmc.kEe	admin	\N	2025-12-15 05:55:32	2025-12-15 12:56:07
8	Outlet 1	outlet@gmail.com	\N	$2y$12$CLk0llB59QR2.0mzjPONxufnvu9l9MaRytlNZuODn/sNRf8tKaSEC	outlet	\N	2025-12-18 07:13:32	2025-12-18 07:14:40
9	Supervisor	supervisor@gmail.com	\N	$2y$12$Tuu9cB7gt2qxB0MRwlxzT.7.7KuuhpKya3otDOkCy5fnWWNZVRakW	SPV	\N	2025-12-18 07:16:38	2025-12-18 07:55:18
10	Outlet 6	outlet6@gmail.com	2026-01-31 06:20:37	$2y$12$x5Nz7gnY7dk5rcFkUdmsae7uD8IfV7.3Ey.4lrbcP2roS5sS.Ur0i	outlet	\N	2026-01-31 06:20:37	2026-01-31 06:20:37
\.


--
-- Name: bahans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bahans_id_seq', 77, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: marinasi_batches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marinasi_batches_id_seq', 1, false);


--
-- Name: marinasi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marinasi_id_seq', 1, false);


--
-- Name: marinasi_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marinasi_items_id_seq', 41, true);


--
-- Name: marinasis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marinasis_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 9, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: bahans bahans_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bahans
    ADD CONSTRAINT bahans_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: marinasi_batches marinasi_batches_kode_batch_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_batches
    ADD CONSTRAINT marinasi_batches_kode_batch_unique UNIQUE (kode_batch);


--
-- Name: marinasi_batches marinasi_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_batches
    ADD CONSTRAINT marinasi_batches_pkey PRIMARY KEY (id);


--
-- Name: marinasi_items marinasi_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_items
    ADD CONSTRAINT marinasi_items_pkey PRIMARY KEY (id);


--
-- Name: marinasi marinasi_kode_batch_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi
    ADD CONSTRAINT marinasi_kode_batch_unique UNIQUE (kode_batch);


--
-- Name: marinasi marinasi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi
    ADD CONSTRAINT marinasi_pkey PRIMARY KEY (id);


--
-- Name: marinasis marinasis_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasis
    ADD CONSTRAINT marinasis_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- PostgreSQL database dump complete
--

\unrestrict qww8LqFWxf5BcmYseh0Rn4ym8E3hcco9aQe2MbTEdyjFCB1zu3ks6rBRKTV0pno

