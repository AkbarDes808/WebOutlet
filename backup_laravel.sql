--
-- PostgreSQL database dump
--

\restrict U95NahgKEFtUPJRC1qwyHvPyYXTlYWBIZMkcyGIt13y7ErJ1KRi90OjmMh95X5a

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

--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


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
-- Name: marinasi_batch_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.marinasi_batch_items (
    id bigint NOT NULL,
    marinasi_batch_id bigint NOT NULL,
    bahan character varying(255) NOT NULL,
    total numeric(10,2) NOT NULL,
    sisa numeric(10,2) NOT NULL,
    satuan character varying(10) DEFAULT 'kg'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marinasi_batch_items OWNER TO postgres;

--
-- Name: marinasi_batch_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.marinasi_batch_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marinasi_batch_items_id_seq OWNER TO postgres;

--
-- Name: marinasi_batch_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.marinasi_batch_items_id_seq OWNED BY public.marinasi_batch_items.id;


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
    bahan character varying(255) NOT NULL,
    jenis character varying(255) NOT NULL,
    penggunaan character varying(255) NOT NULL,
    banyak numeric(15,2) DEFAULT 0 NOT NULL,
    satuan character varying(50) NOT NULL,
    total numeric(15,2) DEFAULT 0 NOT NULL,
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
-- Name: menus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.menus (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    price integer NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.menus OWNER TO postgres;

--
-- Name: menus_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.menus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menus_id_seq OWNER TO postgres;

--
-- Name: menus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.menus_id_seq OWNED BY public.menus.id;


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
-- Name: shift_closings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.shift_closings (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    outlet character varying(255) NOT NULL,
    kasir character varying(255) NOT NULL,
    tanggal date NOT NULL,
    waktu_mulai time(0) without time zone NOT NULL,
    waktu_selesai time(0) without time zone NOT NULL,
    total_transaksi integer DEFAULT 0 NOT NULL,
    total_penjualan numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    cash_total numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    cash_orders integer DEFAULT 0 NOT NULL,
    qris_total numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    qris_orders integer DEFAULT 0 NOT NULL,
    uang_modal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    actual_cash numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    selisih numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    pengeluaran_lainnya numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    catatan text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status_shift character varying(20),
    total_kembalian bigint DEFAULT 0 NOT NULL
);


ALTER TABLE public.shift_closings OWNER TO postgres;

--
-- Name: shift_closings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.shift_closings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.shift_closings_id_seq OWNER TO postgres;

--
-- Name: shift_closings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.shift_closings_id_seq OWNED BY public.shift_closings.id;


--
-- Name: transaction_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transaction_items (
    id bigint NOT NULL,
    transaction_id bigint NOT NULL,
    menu_name character varying(255) NOT NULL,
    price integer NOT NULL,
    qty integer NOT NULL,
    subtotal integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.transaction_items OWNER TO postgres;

--
-- Name: transaction_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transaction_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transaction_items_id_seq OWNER TO postgres;

--
-- Name: transaction_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transaction_items_id_seq OWNED BY public.transaction_items.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    order_number character varying(255) NOT NULL,
    user_id bigint NOT NULL,
    nama_outlet character varying(255) NOT NULL,
    subtotal integer NOT NULL,
    tax integer DEFAULT 0 NOT NULL,
    total integer NOT NULL,
    status character varying(255) DEFAULT 'paid'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kasir_id bigint,
    payment_method character varying(20),
    kode character varying(50),
    change_amount bigint DEFAULT 0 NOT NULL,
    received_amount bigint DEFAULT 0 NOT NULL,
    payment_amount integer DEFAULT 0
);


ALTER TABLE public.transactions OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transactions_id_seq OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


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
    shift_started_at timestamp without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'SPV'::character varying, 'Event'::character varying, 'outlet 1'::character varying, 'outlet 2'::character varying, 'outlet 3'::character varying, 'outlet 4'::character varying, 'outlet 5'::character varying, 'outlet 6'::character varying, 'outlet 7'::character varying])::text[])))
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
-- Name: marinasi_batch_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_batch_items ALTER COLUMN id SET DEFAULT nextval('public.marinasi_batch_items_id_seq'::regclass);


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
-- Name: menus id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menus ALTER COLUMN id SET DEFAULT nextval('public.menus_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: shift_closings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.shift_closings ALTER COLUMN id SET DEFAULT nextval('public.shift_closings_id_seq'::regclass);


--
-- Name: transaction_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction_items ALTER COLUMN id SET DEFAULT nextval('public.transaction_items_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: bahans; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bahans (id, nama_outlet, tepung_roti, tepung_bumbu, garam, bubuk_cabe, telur, gula, ayam, tepung, teh, beras, cup, kertas_chicken_kecil, kertas_chicken_sedang, kertas_chicken_besar, dus_chicken, dus_chicken_jumbo, plastik_cup_isi_1, plastik_cup_isi_2, plastik_ayam_kecil, plastik_sedang, plastik_tanggung, plastik_besar, plastik_jumbo, created_at, updated_at) FROM stdin;
1	Outlet 1	50.00	40.00	10.00	5.00	300.00	20.00	100.00	25.00	10.00	100.00	500.00	1000.00	800.00	500.00	100.00	50.00	500.00	300.00	1000.00	700.00	500.00	300.00	100.00	2026-06-17 10:45:43	2026-06-17 10:45:43
2	Outlet 2	45.00	35.00	8.00	4.00	250.00	18.00	80.00	20.00	8.00	90.00	450.00	900.00	700.00	450.00	90.00	40.00	450.00	250.00	900.00	650.00	450.00	250.00	90.00	2026-06-17 10:45:43	2026-06-17 10:45:43
3	Outlet 3	60.00	50.00	12.00	6.00	350.00	25.00	120.00	30.00	12.00	120.00	600.00	1200.00	900.00	600.00	120.00	60.00	600.00	350.00	1200.00	800.00	600.00	350.00	120.00	2026-06-17 10:45:43	2026-06-17 10:45:43
4	Outlet 4	40.00	30.00	7.00	3.00	200.00	15.00	70.00	18.00	7.00	80.00	400.00	800.00	600.00	400.00	80.00	35.00	400.00	200.00	800.00	500.00	350.00	200.00	80.00	2026-06-17 10:45:43	2026-06-17 10:45:43
5	Outlet 5	55.00	45.00	11.00	5.00	320.00	22.00	110.00	28.00	11.00	110.00	550.00	1100.00	850.00	550.00	110.00	55.00	550.00	320.00	1100.00	750.00	550.00	320.00	110.00	2026-06-17 10:45:43	2026-06-17 10:45:43
6	Outlet 6	48.00	38.00	9.00	4.00	280.00	19.00	95.00	22.00	9.00	95.00	480.00	950.00	750.00	480.00	95.00	45.00	480.00	280.00	950.00	680.00	480.00	280.00	95.00	2026-06-17 10:45:43	2026-06-17 10:45:43
7	Outlet 1	51.00	40.00	10.00	5.00	300.00	20.00	100.00	25.00	10.00	100.00	500.00	1000.00	800.00	500.00	100.00	50.00	500.00	300.00	1000.00	700.00	500.00	300.00	100.00	2026-07-03 05:43:47	2026-07-03 05:43:47
8	Outlet 1	50.00	40.00	10.00	5.00	300.00	20.00	100.00	25.00	10.00	100.00	500.00	1000.00	800.00	500.00	100.00	50.00	500.00	300.00	1000.00	700.00	500.00	300.00	100.00	2026-07-03 05:44:08	2026-07-03 05:44:08
9	Outlet 1	51.00	40.00	10.00	5.00	300.00	20.00	100.00	25.00	10.00	100.00	500.00	1000.00	800.00	500.00	100.00	50.00	500.00	300.00	1000.00	700.00	500.00	300.00	100.00	2026-07-03 08:24:08	2026-07-03 08:24:08
10	Outlet 1	50.00	40.00	10.00	5.00	300.00	20.00	100.00	25.00	10.00	100.00	500.00	1000.00	800.00	500.00	100.00	50.00	500.00	300.00	1000.00	700.00	500.00	300.00	100.00	2026-07-03 08:24:09	2026-07-03 08:24:09
11	Outlet 3	60.00	50.00	12.00	16.00	350.00	25.00	120.00	30.00	12.00	120.00	600.00	1200.00	900.00	600.00	120.00	60.00	600.00	350.00	1200.00	800.00	600.00	350.00	120.00	2026-07-26 17:07:28	2026-07-26 17:07:28
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
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
-- Data for Name: marinasi_batch_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasi_batch_items (id, marinasi_batch_id, bahan, total, sisa, satuan, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: marinasi_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasi_batches (id, kode_batch, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: marinasi_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasi_items (id, marinasi_id, bahan, jenis, penggunaan, banyak, satuan, total, created_at, updated_at) FROM stdin;
1	100001	Marinasi A	Bumbu Tepung Marinasi	Tambah Stok	1000.00	Gram	1000.00	2026-06-17 10:26:28.098074	2026-06-17 10:26:28.098074
2	100001	Marinasi B	Bumbu Tepung Marinasi	Tambah Stok	500.00	Gram	500.00	2026-06-17 10:26:28.098074	2026-06-17 10:26:28.098074
3	483351	Marinasi A	Bumbu Tepung Marinasi	Tambah Stok	1.00	Gram	1.00	2026-07-03 06:52:53	2026-07-03 06:52:53
4	221575	Marinasi A	Bumbu Tepung Marinasi	Tambah Stok	1.00	Gram	1.00	2026-07-03 08:24:11	2026-07-03 08:24:11
5	739142	Marinasi A	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-1000.00	2026-07-26 17:09:44	2026-07-26 17:09:44
6	739142	Marinasi B	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-2.00	2026-07-26 17:09:44	2026-07-26 17:09:44
7	739142	Marinasi & Lapis C	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-10.00	2026-07-26 17:09:44	2026-07-26 17:09:44
8	739142	Marinasi D	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-10.00	2026-07-26 17:09:44	2026-07-26 17:09:44
9	739142	Marinasi E	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-10.00	2026-07-26 17:09:44	2026-07-26 17:09:44
10	739142	Marinasi F	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-10.00	2026-07-26 17:09:44	2026-07-26 17:09:44
11	739142	Marinasi G	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-10.00	2026-07-26 17:09:44	2026-07-26 17:09:44
12	739142	Marinasi H	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-10.00	2026-07-26 17:09:44	2026-07-26 17:09:44
13	739142	Marinasi I	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-12.50	2026-07-26 17:09:44	2026-07-26 17:09:44
14	739142	Marinasi & Lapis J	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-30.00	2026-07-26 17:09:44	2026-07-26 17:09:44
15	739142	Marinasi K	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-600.00	2026-07-26 17:09:44	2026-07-26 17:09:44
16	739142	Marinasi & Lapis L	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-200.00	2026-07-26 17:09:44	2026-07-26 17:09:44
17	739142	Marinasi & Lapis M	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-100.00	2026-07-26 17:09:44	2026-07-26 17:09:44
18	739142	Marinasi & Lapis N	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-700.00	2026-07-26 17:09:44	2026-07-26 17:09:44
19	739142	Marinasi O	Bumbu Tepung Marinasi	Ayam	1500.00	Pieces	-100.00	2026-07-26 17:09:44	2026-07-26 17:09:44
20	621929	Lapis A	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-7070.00	2026-07-26 17:10:44	2026-07-26 17:10:44
21	621929	Marinasi & Lapis C	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-70.00	2026-07-26 17:10:44	2026-07-26 17:10:44
22	621929	Marinasi & Lapis J	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-150.00	2026-07-26 17:10:44	2026-07-26 17:10:44
23	621929	Marinasi & Lapis L	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-5000.00	2026-07-26 17:10:44	2026-07-26 17:10:44
24	621929	Marinasi & Lapis M	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-2800.00	2026-07-26 17:10:44	2026-07-26 17:10:44
25	621929	Marinasi & Lapis N	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-3650.00	2026-07-26 17:10:44	2026-07-26 17:10:44
26	621929	Lapis P	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-500.00	2026-07-26 17:10:44	2026-07-26 17:10:44
27	621929	Lapis Q	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-800.00	2026-07-26 17:10:44	2026-07-26 17:10:44
28	621929	Lapis R	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-1600.00	2026-07-26 17:10:44	2026-07-26 17:10:44
29	621929	Lapis S	Bumbu Tepung Lapis	Simpan Karung	25.00	Kilogram	-1000.00	2026-07-26 17:10:44	2026-07-26 17:10:44
30	302241	Lapis P	Bumbu Tepung Lapis	Tambah Stok	500.00	Gram	500.00	2026-07-26 17:11:31	2026-07-26 17:11:31
31	543925	Marinasi A	Bumbu Tepung Marinasi	Tambah Stok	7068.00	Gram	7068.00	2026-07-26 17:11:57	2026-07-26 17:11:57
\.


--
-- Data for Name: marinasis; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.marinasis (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: menus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.menus (id, name, price, is_active, created_at, updated_at) FROM stdin;
1	Nasi	3000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
2	Sambel	3000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
3	Es Teh	3000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
4	Ayam Original	9000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
5	Paket Geprek + Es	17000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
6	Paket Ori + Es	14000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
7	Ayam Geprek	12000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
8	Ayam Nasi	12000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
9	Ayam Geprek + Nasi	15000	t	2026-06-13 20:05:27	2026-06-13 20:05:27
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
10	2026_04_25_213845_create_menus_table	1
11	2026_04_25_213846_create_transactions_table	1
12	2026_04_25_213852_create_transaction_items_table	1
13	2026_04_30_155354_add_kasir_id_to_transactions_table	1
14	2026_05_16_180333_create_shift_closings_table	1
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
\.


--
-- Data for Name: shift_closings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.shift_closings (id, user_id, outlet, kasir, tanggal, waktu_mulai, waktu_selesai, total_transaksi, total_penjualan, cash_total, cash_orders, qris_total, qris_orders, uang_modal, actual_cash, selisih, pengeluaran_lainnya, catatan, created_at, updated_at, status_shift, total_kembalian) FROM stdin;
11	1	outlet 1	Pusat	2026-08-24	19:14:06	14:04:55	4	92000.00	77000.00	3	15000.00	1	0.00	45000.00	-32000.00	30000.00	Thuryutyu\r\nThiago y	2026-08-24 14:04:55	2026-08-24 14:04:55	\N	0
12	6	outlet 6	Larangan	2026-08-24	11:58:59	19:01:16	37	790000.00	658000.00	31	132000.00	6	0.00	658000.00	0.00	0.00	Selisih seribu karena qris hrusnya 10k	2026-08-24 19:01:16	2026-08-24 19:01:16	\N	0
13	6	outlet 6	Larangan	2026-08-25	11:58:59	18:57:47	44	851000.00	788000.00	40	63000.00	4	0.00	788000.00	0.00	0.00	\N	2026-08-25 18:57:47	2026-08-25 18:57:47	\N	0
14	7	outlet 7	Unsoed	2026-08-26	12:58:35	12:31:56	0	0.00	0.00	0	0.00	0	0.00	0.00	0.00	0.00	\N	2026-08-26 12:31:56	2026-08-26 12:31:56	\N	0
15	6	outlet 6	Larangan	2026-08-26	11:58:59	19:28:29	22	428000.00	359000.00	18	69000.00	4	0.00	359000.00	0.00	0.00	\N	2026-08-26 19:28:29	2026-08-26 19:28:29	\N	0
\.


--
-- Data for Name: transaction_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transaction_items (id, transaction_id, menu_name, price, qty, subtotal, created_at, updated_at) FROM stdin;
1	1	Ayam Geprek	12000	1	12000	2026-08-22 19:27:39	2026-08-22 19:27:39
2	1	Ayam Geprek + Nasi	15000	1	15000	2026-08-22 19:27:39	2026-08-22 19:27:39
3	2	Ayam Geprek	12000	1	12000	2026-08-22 19:52:55	2026-08-22 19:52:55
4	2	Ayam Geprek + Nasi	15000	1	15000	2026-08-22 19:52:55	2026-08-22 19:52:55
5	3	Ayam Geprek	12000	1	12000	2026-08-22 19:55:03	2026-08-22 19:55:03
6	3	Ayam Geprek + Nasi	15000	1	15000	2026-08-22 19:55:03	2026-08-22 19:55:03
7	4	Paket Geprek + Es	17000	1	17000	2026-08-22 19:55:11	2026-08-22 19:55:11
8	4	Paket Ori + Es	14000	1	14000	2026-08-22 19:55:11	2026-08-22 19:55:11
9	5	Ayam Geprek	12000	1	12000	2026-08-22 20:02:58	2026-08-22 20:02:58
10	5	Ayam Geprek + Nasi	15000	1	15000	2026-08-22 20:02:58	2026-08-22 20:02:58
11	6	Sambel	3000	1	3000	2026-08-22 20:03:03	2026-08-22 20:03:03
12	6	Paket Ori + Es	14000	1	14000	2026-08-22 20:03:03	2026-08-22 20:03:03
13	7	Nasi	3000	2	6000	2026-08-22 20:04:07	2026-08-22 20:04:07
14	7	Es Teh	3000	2	6000	2026-08-22 20:04:07	2026-08-22 20:04:07
15	7	Ayam Original	9000	1	9000	2026-08-22 20:04:07	2026-08-22 20:04:07
16	8	Es Teh	3000	1	3000	2026-08-22 20:16:39	2026-08-22 20:16:39
17	8	Ayam Original	9000	1	9000	2026-08-22 20:16:39	2026-08-22 20:16:39
18	8	Ayam Geprek	12000	1	12000	2026-08-22 20:16:39	2026-08-22 20:16:39
19	8	Ayam Geprek + Nasi	15000	1	15000	2026-08-22 20:16:39	2026-08-22 20:16:39
20	9	Ayam Geprek	12000	1	12000	2026-08-22 20:16:48	2026-08-22 20:16:48
21	9	Ayam Geprek + Nasi	15000	1	15000	2026-08-22 20:16:48	2026-08-22 20:16:48
22	10	Ayam Geprek	12000	1	12000	2026-08-23 09:47:00	2026-08-23 09:47:00
23	10	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 09:47:00	2026-08-23 09:47:00
24	11	Ayam Geprek	12000	1	12000	2026-08-23 09:48:00	2026-08-23 09:48:00
25	11	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 09:48:00	2026-08-23 09:48:00
26	12	Ayam Geprek	12000	1	12000	2026-08-23 09:49:08	2026-08-23 09:49:08
27	12	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 09:49:08	2026-08-23 09:49:08
28	13	Es Teh	3000	1	3000	2026-08-23 09:51:42	2026-08-23 09:51:42
29	13	Ayam Original	9000	1	9000	2026-08-23 09:51:42	2026-08-23 09:51:42
30	14	Paket Geprek + Es	17000	1	17000	2026-08-23 10:09:27	2026-08-23 10:09:27
31	14	Paket Ori + Es	14000	1	14000	2026-08-23 10:09:27	2026-08-23 10:09:27
32	15	Ayam Geprek + Nasi	15000	2	30000	2026-08-23 11:11:34	2026-08-23 11:11:34
33	16	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 11:11:48	2026-08-23 11:11:48
34	17	Ayam Geprek	12000	1	12000	2026-08-23 11:14:15	2026-08-23 11:14:15
35	18	Ayam Geprek	12000	1	12000	2026-08-23 11:38:05	2026-08-23 11:38:05
36	19	Paket Geprek + Es	17000	1	17000	2026-08-23 11:38:33	2026-08-23 11:38:33
37	20	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 12:00:14	2026-08-23 12:00:14
38	21	Paket Ori + Es	14000	1	14000	2026-08-23 12:00:33	2026-08-23 12:00:33
39	22	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 12:08:36	2026-08-23 12:08:36
40	23	Paket Ori + Es	14000	1	14000	2026-08-23 12:08:53	2026-08-23 12:08:53
41	24	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 12:38:54	2026-08-23 12:38:54
42	25	Paket Geprek + Es	17000	1	17000	2026-08-23 12:39:19	2026-08-23 12:39:19
43	26	Ayam Geprek + Nasi	15000	1	15000	2026-08-23 13:00:00	2026-08-23 13:00:00
44	27	Paket Geprek + Es	17000	1	17000	2026-08-23 13:00:32	2026-08-23 13:00:32
45	28	Es Teh	3000	1	3000	2026-08-23 13:46:41	2026-08-23 13:46:41
46	28	Ayam Geprek	12000	1	12000	2026-08-23 13:46:41	2026-08-23 13:46:41
47	29	Ayam Nasi	12000	1	12000	2026-08-23 13:50:27	2026-08-23 13:50:27
48	30	Ayam Geprek	12000	4	48000	2026-08-24 08:15:27	2026-08-24 08:15:27
49	31	Ayam Nasi	12000	1	12000	2026-08-24 09:12:39	2026-08-24 09:12:39
50	32	Ayam Original	9000	2	18000	2026-08-24 09:15:12	2026-08-24 09:15:12
51	33	Ayam Nasi	12000	1	12000	2026-08-24 09:43:00	2026-08-24 09:43:00
52	34	Ayam Nasi	12000	2	24000	2026-08-24 10:01:05	2026-08-24 10:01:05
53	35	Nasi	3000	1	3000	2026-08-24 10:02:52	2026-08-24 10:02:52
54	36	Ayam Original	9000	1	9000	2026-08-24 10:16:18	2026-08-24 10:16:18
55	37	Ayam Nasi	12000	1	12000	2026-08-24 10:26:56	2026-08-24 10:26:56
56	38	Ayam Original	9000	1	9000	2026-08-24 10:34:29	2026-08-24 10:34:29
57	39	Paket Ori + Es	14000	1	14000	2026-08-24 10:36:37	2026-08-24 10:36:37
58	40	Ayam Original	9000	2	18000	2026-08-24 10:37:44	2026-08-24 10:37:44
59	41	Ayam Geprek + Nasi	15000	1	15000	2026-08-24 10:43:32	2026-08-24 10:43:32
60	42	Ayam Geprek	12000	1	12000	2026-08-24 10:46:09	2026-08-24 10:46:09
61	43	Ayam Original	9000	1	9000	2026-08-24 11:58:45	2026-08-24 11:58:45
62	43	Ayam Geprek + Nasi	15000	3	45000	2026-08-24 11:58:45	2026-08-24 11:58:45
63	44	Ayam Original	9000	4	36000	2026-08-24 12:56:42	2026-08-24 12:56:42
64	45	Ayam Nasi	12000	2	24000	2026-08-24 13:27:08	2026-08-24 13:27:08
65	46	Ayam Original	9000	1	9000	2026-08-24 13:27:53	2026-08-24 13:27:53
66	47	Ayam Original	9000	2	18000	2026-08-24 13:40:27	2026-08-24 13:40:27
67	48	Ayam Original	9000	1	9000	2026-08-24 13:41:32	2026-08-24 13:41:32
68	49	Sambel	3000	1	3000	2026-08-24 14:01:26	2026-08-24 14:01:26
69	49	Paket Ori + Es	14000	1	14000	2026-08-24 14:01:26	2026-08-24 14:01:26
70	50	Nasi	3000	1	3000	2026-08-24 14:02:31	2026-08-24 14:02:31
71	50	Ayam Nasi	12000	1	12000	2026-08-24 14:02:31	2026-08-24 14:02:31
72	51	Ayam Original	9000	3	27000	2026-08-24 14:19:58	2026-08-24 14:19:58
73	52	Ayam Original	9000	1	9000	2026-08-24 14:32:24	2026-08-24 14:32:24
74	52	Ayam Nasi	12000	2	24000	2026-08-24 14:32:24	2026-08-24 14:32:24
75	53	Ayam Geprek	12000	1	12000	2026-08-24 14:44:14	2026-08-24 14:44:14
76	53	Ayam Nasi	12000	1	12000	2026-08-24 14:44:14	2026-08-24 14:44:14
77	54	Ayam Original	9000	5	45000	2026-08-24 15:01:13	2026-08-24 15:01:13
78	55	Ayam Nasi	12000	1	12000	2026-08-24 15:25:13	2026-08-24 15:25:13
79	56	Ayam Nasi	12000	3	36000	2026-08-24 16:14:45	2026-08-24 16:14:45
80	56	Ayam Geprek + Nasi	15000	2	30000	2026-08-24 16:14:45	2026-08-24 16:14:45
81	57	Ayam Original	9000	3	27000	2026-08-24 16:16:18	2026-08-24 16:16:18
82	58	Ayam Original	9000	1	9000	2026-08-24 16:27:50	2026-08-24 16:27:50
83	59	Ayam Original	9000	2	18000	2026-08-24 16:50:04	2026-08-24 16:50:04
84	60	Ayam Original	9000	1	9000	2026-08-24 16:57:16	2026-08-24 16:57:16
85	61	Ayam Original	9000	2	18000	2026-08-24 17:15:10	2026-08-24 17:15:10
86	62	Ayam Original	9000	3	27000	2026-08-24 17:16:37	2026-08-24 17:16:37
87	63	Ayam Original	9000	3	27000	2026-08-24 17:16:53	2026-08-24 17:16:53
88	64	Ayam Geprek + Nasi	15000	1	15000	2026-08-24 17:19:21	2026-08-24 17:19:21
89	65	Ayam Original	9000	2	18000	2026-08-24 17:29:12	2026-08-24 17:29:12
90	66	Nasi	3000	2	6000	2026-08-24 17:36:34	2026-08-24 17:36:34
91	67	Ayam Original	9000	2	18000	2026-08-24 17:42:36	2026-08-24 17:42:36
92	68	Ayam Original	9000	3	27000	2026-08-24 17:51:48	2026-08-24 17:51:48
93	69	Ayam Original	9000	1	9000	2026-08-24 17:57:38	2026-08-24 17:57:38
94	70	Ayam Original	9000	2	18000	2026-08-24 18:30:16	2026-08-24 18:30:16
95	70	Paket Ori + Es	14000	1	14000	2026-08-24 18:30:16	2026-08-24 18:30:16
96	70	Ayam Nasi	12000	1	12000	2026-08-24 18:30:16	2026-08-24 18:30:16
97	71	Ayam Original	9000	3	27000	2026-08-24 18:49:39	2026-08-24 18:49:39
98	72	Ayam Original	9000	3	27000	2026-08-25 09:24:07	2026-08-25 09:24:07
99	73	Ayam Original	9000	1	9000	2026-08-25 10:15:30	2026-08-25 10:15:30
100	74	Ayam Nasi	12000	5	60000	2026-08-25 10:46:26	2026-08-25 10:46:26
101	75	Ayam Original	9000	2	18000	2026-08-25 11:04:24	2026-08-25 11:04:24
102	75	Ayam Nasi	12000	1	12000	2026-08-25 11:04:24	2026-08-25 11:04:24
103	76	Ayam Original	9000	1	9000	2026-08-25 11:11:05	2026-08-25 11:11:05
104	77	Ayam Original	9000	3	27000	2026-08-25 11:14:00	2026-08-25 11:14:00
105	78	Ayam Original	9000	2	18000	2026-08-25 11:21:08	2026-08-25 11:21:08
106	79	Nasi	3000	1	3000	2026-08-25 11:40:42	2026-08-25 11:40:42
107	80	Nasi	3000	1	3000	2026-08-25 11:47:41	2026-08-25 11:47:41
108	81	Ayam Geprek + Nasi	15000	1	15000	2026-08-25 12:41:45	2026-08-25 12:41:45
109	82	Ayam Nasi	12000	1	12000	2026-08-25 12:42:01	2026-08-25 12:42:01
110	83	Ayam Original	9000	2	18000	2026-08-25 12:42:15	2026-08-25 12:42:15
111	84	Paket Ori + Es	14000	1	14000	2026-08-25 12:42:31	2026-08-25 12:42:31
112	85	Es Teh	3000	1	3000	2026-08-25 12:43:00	2026-08-25 12:43:00
113	85	Ayam Geprek	12000	1	12000	2026-08-25 12:43:00	2026-08-25 12:43:00
114	86	Paket Ori + Es	14000	2	28000	2026-08-25 12:52:56	2026-08-25 12:52:56
115	87	Ayam Geprek	12000	1	12000	2026-08-25 13:02:20	2026-08-25 13:02:20
116	88	Ayam Original	9000	3	27000	2026-08-25 13:02:56	2026-08-25 13:02:56
117	89	Ayam Original	9000	3	27000	2026-08-25 13:03:18	2026-08-25 13:03:18
118	90	Ayam Geprek	12000	2	24000	2026-08-25 13:29:07	2026-08-25 13:29:07
119	91	Sambel	3000	1	3000	2026-08-25 13:29:17	2026-08-25 13:29:17
120	92	Ayam Original	9000	3	27000	2026-08-25 13:48:25	2026-08-25 13:48:25
121	93	Ayam Original	9000	5	45000	2026-08-25 13:57:30	2026-08-25 13:57:30
122	94	Ayam Nasi	12000	1	12000	2026-08-25 14:26:55	2026-08-25 14:26:55
123	95	Ayam Original	9000	2	18000	2026-08-25 14:42:23	2026-08-25 14:42:23
124	96	Ayam Geprek	12000	1	12000	2026-08-25 14:53:45	2026-08-25 14:53:45
125	97	Ayam Geprek	12000	1	12000	2026-08-25 14:59:23	2026-08-25 14:59:23
126	98	Ayam Geprek	12000	1	12000	2026-08-25 15:21:34	2026-08-25 15:21:34
127	99	Ayam Geprek	12000	1	12000	2026-08-25 15:32:20	2026-08-25 15:32:20
128	100	Ayam Original	9000	1	9000	2026-08-25 15:37:22	2026-08-25 15:37:22
129	100	Ayam Geprek	12000	1	12000	2026-08-25 15:37:22	2026-08-25 15:37:22
130	101	Paket Geprek + Es	17000	1	17000	2026-08-25 16:01:19	2026-08-25 16:01:19
131	102	Paket Ori + Es	14000	1	14000	2026-08-25 16:05:41	2026-08-25 16:05:41
132	103	Paket Ori + Es	14000	1	14000	2026-08-25 16:19:51	2026-08-25 16:19:51
133	104	Ayam Geprek	12000	1	12000	2026-08-25 16:26:01	2026-08-25 16:26:01
134	105	Paket Ori + Es	14000	1	14000	2026-08-25 16:29:32	2026-08-25 16:29:32
135	106	Ayam Original	9000	2	18000	2026-08-25 17:03:14	2026-08-25 17:03:14
136	107	Ayam Original	9000	1	9000	2026-08-25 17:10:14	2026-08-25 17:10:14
137	108	Ayam Original	9000	2	18000	2026-08-25 17:14:11	2026-08-25 17:14:11
138	109	Ayam Original	9000	2	18000	2026-08-25 17:19:31	2026-08-25 17:19:31
139	110	Ayam Original	9000	1	9000	2026-08-25 17:21:35	2026-08-25 17:21:35
140	111	Ayam Original	9000	1	9000	2026-08-25 17:34:08	2026-08-25 17:34:08
141	112	Ayam Original	9000	1	9000	2026-08-25 17:58:23	2026-08-25 17:58:23
142	112	Ayam Geprek	12000	1	12000	2026-08-25 17:58:23	2026-08-25 17:58:23
143	113	Ayam Original	9000	5	45000	2026-08-25 18:04:39	2026-08-25 18:04:39
144	114	Ayam Original	9000	6	54000	2026-08-25 18:26:04	2026-08-25 18:26:04
145	115	Ayam Original	9000	3	27000	2026-08-25 18:48:33	2026-08-25 18:48:33
146	116	Es Teh	3000	1	3000	2026-08-26 09:38:58	2026-08-26 09:38:58
147	116	Ayam Original	9000	3	27000	2026-08-26 09:38:58	2026-08-26 09:38:58
148	117	Ayam Geprek + Nasi	15000	1	15000	2026-08-26 11:13:07	2026-08-26 11:13:07
149	118	Ayam Original	9000	1	9000	2026-08-26 11:32:06	2026-08-26 11:32:06
150	119	Ayam Original	9000	1	9000	2026-08-26 11:37:04	2026-08-26 11:37:04
151	120	Ayam Original	9000	2	18000	2026-08-26 12:01:01	2026-08-26 12:01:01
152	121	Paket Ori + Es	14000	2	28000	2026-08-26 12:48:50	2026-08-26 12:48:50
153	122	Paket Geprek + Es	17000	1	17000	2026-08-26 12:50:58	2026-08-26 12:50:58
154	122	Paket Ori + Es	14000	2	28000	2026-08-26 12:50:58	2026-08-26 12:50:58
155	123	Ayam Original	9000	1	9000	2026-08-26 13:09:05	2026-08-26 13:09:05
156	124	Ayam Original	9000	1	9000	2026-08-26 13:47:22	2026-08-26 13:47:22
157	125	Ayam Original	9000	1	9000	2026-08-26 14:07:22	2026-08-26 14:07:22
158	126	Ayam Original	9000	1	9000	2026-08-26 14:29:23	2026-08-26 14:29:23
159	126	Ayam Nasi	12000	1	12000	2026-08-26 14:29:23	2026-08-26 14:29:23
160	127	Ayam Geprek	12000	3	36000	2026-08-26 14:36:35	2026-08-26 14:36:35
161	128	Ayam Original	9000	1	9000	2026-08-26 14:57:05	2026-08-26 14:57:05
162	129	Es Teh	3000	2	6000	2026-08-26 15:07:44	2026-08-26 15:07:44
163	129	Ayam Original	9000	1	9000	2026-08-26 15:07:44	2026-08-26 15:07:44
164	129	Paket Ori + Es	14000	1	14000	2026-08-26 15:07:44	2026-08-26 15:07:44
165	130	Ayam Nasi	12000	1	12000	2026-08-26 15:28:03	2026-08-26 15:28:03
166	131	Ayam Original	9000	1	9000	2026-08-26 15:30:23	2026-08-26 15:30:23
167	132	Ayam Original	9000	1	9000	2026-08-26 16:21:46	2026-08-26 16:21:46
168	132	Ayam Geprek	12000	1	12000	2026-08-26 16:21:46	2026-08-26 16:21:46
169	133	Paket Geprek + Es	17000	1	17000	2026-08-26 17:56:52	2026-08-26 17:56:52
170	134	Ayam Original	9000	3	27000	2026-08-26 18:43:30	2026-08-26 18:43:30
171	135	Ayam Nasi	12000	1	12000	2026-08-26 18:45:11	2026-08-26 18:45:11
172	136	Ayam Original	9000	3	27000	2026-08-26 18:45:31	2026-08-26 18:45:31
173	137	Ayam Original	9000	3	27000	2026-08-26 18:45:47	2026-08-26 18:45:47
174	138	Ayam Geprek	12000	1	12000	2026-08-31 01:00:55	2026-08-31 01:00:55
175	138	Ayam Geprek + Nasi	15000	1	15000	2026-08-31 01:00:55	2026-08-31 01:00:55
\.


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transactions (id, order_number, user_id, nama_outlet, subtotal, tax, total, status, created_at, updated_at, kasir_id, payment_method, kode, change_amount, received_amount, payment_amount) FROM stdin;
1	ORD-20260822192739	2	outlet 2	27000	0	27000	paid	2026-08-22 19:27:39	2026-08-22 19:27:39	2	cash	TRX-UET2A5	0	0	27000
2	ORD-20260822195255	2	outlet 2	27000	0	27000	paid	2026-08-22 19:52:55	2026-08-22 19:52:55	2	cash	TRX-2JTTT5	0	0	27000
3	ORD-20260822195503	2	outlet 2	27000	0	27000	paid	2026-08-22 19:55:03	2026-08-22 19:55:03	2	cash	TRX-JJEI94	0	0	27000
4	ORD-20260822195511	2	outlet 2	31000	0	31000	paid	2026-08-22 19:55:11	2026-08-22 19:55:11	2	qris	TRX-MAVKJC	0	0	31000
5	ORD-20260822200258	2	outlet 2	27000	0	27000	paid	2026-08-22 20:02:58	2026-08-22 20:02:58	2	cash	TRX-NYWWL8	0	0	27000
6	ORD-20260822200303	2	outlet 2	17000	0	17000	paid	2026-08-22 20:03:03	2026-08-22 20:03:03	2	cash	TRX-4WKF6A	0	0	17000
7	ORD-20260822200407	2	outlet 2	21000	0	21000	paid	2026-08-22 20:04:07	2026-08-22 20:04:07	2	cash	TRX-CSXKNV	0	0	21000
8	ORD-20260822201639	2	outlet 2	39000	0	39000	paid	2026-08-22 20:16:39	2026-08-22 20:16:39	2	cash	TRX-LJ5U9F	0	0	39000
9	ORD-20260822201648	2	outlet 2	27000	0	27000	paid	2026-08-22 20:16:48	2026-08-22 20:16:48	2	qris	TRX-FY6M8Z	0	0	27000
10	ORD-20260823094700	2	outlet 2	27000	0	27000	paid	2026-08-23 09:47:00	2026-08-23 09:47:00	2	cash	TRX-YOQ4YX	0	0	27000
11	ORD-20260823094800	2	outlet 2	27000	0	27000	paid	2026-08-23 09:48:00	2026-08-23 09:48:00	2	cash	TRX-PRMVTO	0	0	27000
12	ORD-20260823094908	2	outlet 2	27000	0	27000	paid	2026-08-23 09:49:08	2026-08-23 09:49:08	2	cash	TRX-PB7QGN	0	0	27000
13	ORD-20260823095142	2	outlet 2	12000	0	12000	paid	2026-08-23 09:51:42	2026-08-23 09:51:42	2	qris	TRX-WP91FA	0	0	12000
14	ORD-20260823100927	2	outlet 2	31000	0	31000	paid	2026-08-23 10:09:27	2026-08-23 10:09:27	2	cash	TRX-BYC311	0	0	31000
15	ORD-20260823111134	3	outlet 3	30000	0	30000	paid	2026-08-23 11:11:34	2026-08-23 11:11:34	3	cash	TRX-WBUNDV	0	0	30000
16	ORD-20260823111148	3	outlet 3	15000	0	15000	paid	2026-08-23 11:11:48	2026-08-23 11:11:48	3	qris	TRX-L6PGQQ	0	0	15000
17	ORD-20260823111415	3	outlet 3	12000	0	12000	paid	2026-08-23 11:14:15	2026-08-23 11:14:15	3	cash	TRX-UPSOMB	0	0	12000
18	ORD-20260823113805	4	outlet 4	12000	0	12000	paid	2026-08-23 11:38:05	2026-08-23 11:38:05	4	cash	TRX-JYYCUF	38000	0	50000
19	ORD-20260823113833	4	outlet 4	17000	0	17000	paid	2026-08-23 11:38:33	2026-08-23 11:38:33	4	qris	TRX-7GXDQA	0	0	17000
20	ORD-20260823120014	6	outlet 6	15000	0	15000	paid	2026-08-23 12:00:14	2026-08-23 12:00:14	6	cash	TRX-NMPZHC	0	0	15000
21	ORD-20260823120033	6	outlet 6	14000	0	14000	paid	2026-08-23 12:00:33	2026-08-23 12:00:33	6	qris	TRX-ALVJGI	0	0	14000
22	ORD-20260823120836	1	outlet 1	15000	0	15000	paid	2026-08-23 12:08:36	2026-08-23 12:08:36	1	cash	TRX-FVSSV8	5000	0	20000
23	ORD-20260823120853	1	outlet 1	14000	0	14000	paid	2026-08-23 12:08:53	2026-08-23 12:08:53	1	qris	TRX-QDMFRQ	0	0	14000
24	ORD-20260823123854	5	outlet 5	15000	0	15000	paid	2026-08-23 12:38:54	2026-08-23 12:38:54	5	cash	TRX-PRXXMI	35000	0	50000
25	ORD-20260823123919	5	outlet 5	17000	0	17000	paid	2026-08-23 12:39:19	2026-08-23 12:39:19	5	qris	TRX-DS75NE	0	0	17000
26	ORD-20260823130000	7	outlet 7	15000	0	15000	paid	2026-08-23 13:00:00	2026-08-23 13:00:00	7	cash	TRX-IY7UIU	35000	0	50000
27	ORD-20260823130032	7	outlet 7	17000	0	17000	paid	2026-08-23 13:00:32	2026-08-23 13:00:32	7	qris	TRX-MI0DA3	0	0	17000
28	ORD-20260823134641	2	outlet 2	15000	0	15000	paid	2026-08-23 13:46:41	2026-08-23 13:46:41	2	cash	TRX-Y62DH5	5000	0	20000
29	ORD-20260823135027	2	outlet 2	12000	0	12000	paid	2026-08-23 13:50:27	2026-08-23 13:50:27	2	qris	TRX-SKF3DG	0	0	12000
30	ORD-20260824081527	1	outlet 1	48000	0	48000	paid	2026-08-24 08:15:27	2026-08-24 08:15:27	1	cash	TRX-1YK2KG	2000	0	50000
31	ORD-20260824091239	1	outlet 1	12000	0	12000	paid	2026-08-24 09:12:39	2026-08-24 09:12:39	1	cash	TRX-JAIM1F	0	0	12000
32	ORD-20260824091512	6	outlet 6	18000	0	18000	paid	2026-08-24 09:15:12	2026-08-24 09:15:12	6	cash	TRX-GMLLWH	0	0	18000
33	ORD-20260824094300	6	outlet 6	12000	0	12000	paid	2026-08-24 09:43:00	2026-08-24 09:43:00	6	cash	TRX-3VRJE3	0	0	12000
34	ORD-20260824100105	6	outlet 6	24000	0	24000	paid	2026-08-24 10:01:05	2026-08-24 10:01:05	6	qris	TRX-8N2DSR	0	0	24000
35	ORD-20260824100252	6	outlet 6	3000	0	3000	paid	2026-08-24 10:02:52	2026-08-24 10:02:52	6	cash	TRX-LFGOVW	0	0	3000
36	ORD-20260824101618	6	outlet 6	9000	0	9000	paid	2026-08-24 10:16:18	2026-08-24 10:16:18	6	cash	TRX-1HKGEH	0	0	9000
37	ORD-20260824102656	6	outlet 6	12000	0	12000	paid	2026-08-24 10:26:56	2026-08-24 10:26:56	6	cash	TRX-IQIJ7C	0	0	12000
38	ORD-20260824103428	6	outlet 6	9000	0	9000	paid	2026-08-24 10:34:28	2026-08-24 10:34:28	6	cash	TRX-YXZJ2M	0	0	9000
39	ORD-20260824103637	6	outlet 6	14000	0	14000	paid	2026-08-24 10:36:37	2026-08-24 10:36:37	6	cash	TRX-2PUUP1	0	0	14000
40	ORD-20260824103744	6	outlet 6	18000	0	18000	paid	2026-08-24 10:37:44	2026-08-24 10:37:44	6	cash	TRX-MLJ0ST	0	0	18000
41	ORD-20260824104332	6	outlet 6	15000	0	15000	paid	2026-08-24 10:43:32	2026-08-24 10:43:32	6	cash	TRX-LFUGIL	0	0	15000
42	ORD-20260824104609	5	outlet 5	12000	0	12000	paid	2026-08-24 10:46:09	2026-08-24 10:46:09	5	cash	TRX-GEQCS7	0	0	12000
43	ORD-20260824115845	6	outlet 6	54000	0	54000	paid	2026-08-24 11:58:45	2026-08-24 11:58:45	6	cash	TRX-EZKVLC	0	0	54000
44	ORD-20260824125642	6	outlet 6	36000	0	36000	paid	2026-08-24 12:56:42	2026-08-24 12:56:42	6	cash	TRX-LMJZBI	0	0	36000
45	ORD-20260824132708	6	outlet 6	24000	0	24000	paid	2026-08-24 13:27:08	2026-08-24 13:27:08	6	qris	TRX-PZUZLH	0	0	24000
46	ORD-20260824132753	6	outlet 6	9000	0	9000	paid	2026-08-24 13:27:53	2026-08-24 13:27:53	6	cash	TRX-DUY13A	0	0	9000
47	ORD-20260824134027	6	outlet 6	18000	0	18000	paid	2026-08-24 13:40:27	2026-08-24 13:40:27	6	cash	TRX-JV1VGX	0	0	18000
48	ORD-20260824134132	6	outlet 6	9000	0	9000	paid	2026-08-24 13:41:32	2026-08-24 13:41:32	6	cash	TRX-WKHRYM	0	0	9000
49	ORD-20260824140126	1	outlet 1	17000	0	17000	paid	2026-08-24 14:01:26	2026-08-24 14:01:26	1	cash	TRX-NF8L9Z	0	0	17000
50	ORD-20260824140231	1	outlet 1	15000	0	15000	paid	2026-08-24 14:02:31	2026-08-24 14:02:31	1	qris	TRX-TNMQED	0	0	15000
51	ORD-20260824141958	6	outlet 6	27000	0	27000	paid	2026-08-24 14:19:58	2026-08-24 14:19:58	6	cash	TRX-ZBF4EU	0	0	27000
52	ORD-20260824143224	6	outlet 6	33000	0	33000	paid	2026-08-24 14:32:24	2026-08-24 14:32:24	6	qris	TRX-TD50MU	0	0	33000
53	ORD-20260824144414	6	outlet 6	24000	0	24000	paid	2026-08-24 14:44:14	2026-08-24 14:44:14	6	qris	TRX-Z5LRGI	0	0	24000
54	ORD-20260824150113	6	outlet 6	45000	0	45000	paid	2026-08-24 15:01:13	2026-08-24 15:01:13	6	cash	TRX-RCVNTZ	0	0	45000
55	ORD-20260824152513	6	outlet 6	12000	0	12000	paid	2026-08-24 15:25:13	2026-08-24 15:25:13	6	cash	TRX-VAYUSW	0	0	12000
56	ORD-20260824161445	6	outlet 6	66000	0	66000	paid	2026-08-24 16:14:45	2026-08-24 16:14:45	6	cash	TRX-JHL4TT	0	0	66000
57	ORD-20260824161618	6	outlet 6	27000	0	27000	paid	2026-08-24 16:16:18	2026-08-24 16:16:18	6	cash	TRX-6WRG66	0	0	27000
58	ORD-20260824162750	6	outlet 6	9000	0	9000	paid	2026-08-24 16:27:50	2026-08-24 16:27:50	6	qris	TRX-BNPVNL	0	0	9000
59	ORD-20260824165004	6	outlet 6	18000	0	18000	paid	2026-08-24 16:50:04	2026-08-24 16:50:04	6	cash	TRX-IE1MWI	0	0	18000
60	ORD-20260824165716	6	outlet 6	9000	0	9000	paid	2026-08-24 16:57:16	2026-08-24 16:57:16	6	cash	TRX-SNRPSJ	0	0	9000
61	ORD-20260824171510	6	outlet 6	18000	0	18000	paid	2026-08-24 17:15:10	2026-08-24 17:15:10	6	cash	TRX-IQJOUZ	0	0	18000
62	ORD-20260824171637	6	outlet 6	27000	0	27000	paid	2026-08-24 17:16:37	2026-08-24 17:16:37	6	cash	TRX-S7FX1M	0	0	27000
63	ORD-20260824171653	6	outlet 6	27000	0	27000	paid	2026-08-24 17:16:53	2026-08-24 17:16:53	6	cash	TRX-QICMVF	0	0	27000
64	ORD-20260824171921	6	outlet 6	15000	0	15000	paid	2026-08-24 17:19:21	2026-08-24 17:19:21	6	cash	TRX-OABOJ6	0	0	15000
65	ORD-20260824172912	6	outlet 6	18000	0	18000	paid	2026-08-24 17:29:12	2026-08-24 17:29:12	6	cash	TRX-2PHJWL	0	0	18000
66	ORD-20260824173634	6	outlet 6	6000	0	6000	paid	2026-08-24 17:36:34	2026-08-24 17:36:34	6	cash	TRX-YLQS6U	0	0	6000
67	ORD-20260824174236	6	outlet 6	18000	0	18000	paid	2026-08-24 17:42:36	2026-08-24 17:42:36	6	qris	TRX-VJ96X6	0	0	18000
68	ORD-20260824175148	6	outlet 6	27000	0	27000	paid	2026-08-24 17:51:48	2026-08-24 17:51:48	6	cash	TRX-VGU28X	0	0	27000
69	ORD-20260824175738	6	outlet 6	9000	0	9000	paid	2026-08-24 17:57:38	2026-08-24 17:57:38	6	cash	TRX-ZEOSJJ	0	0	9000
70	ORD-20260824183016	6	outlet 6	44000	0	44000	paid	2026-08-24 18:30:16	2026-08-24 18:30:16	6	cash	TRX-N8KIDN	0	0	44000
71	ORD-20260824184939	6	outlet 6	27000	0	27000	paid	2026-08-24 18:49:39	2026-08-24 18:49:39	6	cash	TRX-AK63ET	0	0	27000
72	ORD-20260825092407	6	outlet 6	27000	0	27000	paid	2026-08-25 09:24:07	2026-08-25 09:24:07	6	cash	TRX-ECDAJ1	0	0	27000
73	ORD-20260825101530	6	outlet 6	9000	0	9000	paid	2026-08-25 10:15:30	2026-08-25 10:15:30	6	cash	TRX-UVEJ5O	0	0	9000
74	ORD-20260825104626	6	outlet 6	60000	0	60000	paid	2026-08-25 10:46:26	2026-08-25 10:46:26	6	cash	TRX-YIZSDG	0	0	60000
75	ORD-20260825110424	6	outlet 6	30000	0	30000	paid	2026-08-25 11:04:24	2026-08-25 11:04:24	6	cash	TRX-I2CDCL	0	0	30000
76	ORD-20260825111105	6	outlet 6	9000	0	9000	paid	2026-08-25 11:11:05	2026-08-25 11:11:05	6	cash	TRX-KUFEGG	0	0	9000
77	ORD-20260825111400	6	outlet 6	27000	0	27000	paid	2026-08-25 11:14:00	2026-08-25 11:14:00	6	cash	TRX-TWZM4H	0	0	27000
78	ORD-20260825112108	6	outlet 6	18000	0	18000	paid	2026-08-25 11:21:08	2026-08-25 11:21:08	6	cash	TRX-O5SERR	0	0	18000
79	ORD-20260825114042	6	outlet 6	3000	0	3000	paid	2026-08-25 11:40:42	2026-08-25 11:40:42	6	cash	TRX-5KR7WN	0	0	3000
80	ORD-20260825114741	6	outlet 6	3000	0	3000	paid	2026-08-25 11:47:41	2026-08-25 11:47:41	6	cash	TRX-MSXCEI	0	0	3000
81	ORD-20260825124145	6	outlet 6	15000	0	15000	paid	2026-08-25 12:41:45	2026-08-25 12:41:45	6	cash	TRX-ZCJXRW	0	0	15000
82	ORD-20260825124201	6	outlet 6	12000	0	12000	paid	2026-08-25 12:42:01	2026-08-25 12:42:01	6	qris	TRX-0NEPSZ	0	0	12000
83	ORD-20260825124215	6	outlet 6	18000	0	18000	paid	2026-08-25 12:42:15	2026-08-25 12:42:15	6	qris	TRX-A4QF2F	0	0	18000
84	ORD-20260825124231	6	outlet 6	14000	0	14000	paid	2026-08-25 12:42:31	2026-08-25 12:42:31	6	cash	TRX-UBP2AO	0	0	14000
85	ORD-20260825124300	6	outlet 6	15000	0	15000	paid	2026-08-25 12:43:00	2026-08-25 12:43:00	6	cash	TRX-YHD049	0	0	15000
86	ORD-20260825125256	6	outlet 6	28000	0	28000	paid	2026-08-25 12:52:56	2026-08-25 12:52:56	6	cash	TRX-YG1YRF	0	0	28000
87	ORD-20260825130220	6	outlet 6	12000	0	12000	paid	2026-08-25 13:02:20	2026-08-25 13:02:20	6	cash	TRX-07KJZQ	0	0	12000
88	ORD-20260825130256	6	outlet 6	27000	0	27000	paid	2026-08-25 13:02:56	2026-08-25 13:02:56	6	cash	TRX-UNHIIJ	0	0	27000
89	ORD-20260825130318	6	outlet 6	27000	0	27000	paid	2026-08-25 13:03:18	2026-08-25 13:03:18	6	cash	TRX-UP0XSC	0	0	27000
90	ORD-20260825132907	6	outlet 6	24000	0	24000	paid	2026-08-25 13:29:07	2026-08-25 13:29:07	6	cash	TRX-GKD85M	0	0	24000
91	ORD-20260825132917	6	outlet 6	3000	0	3000	paid	2026-08-25 13:29:17	2026-08-25 13:29:17	6	cash	TRX-0HJRBM	0	0	3000
92	ORD-20260825134825	6	outlet 6	27000	0	27000	paid	2026-08-25 13:48:25	2026-08-25 13:48:25	6	cash	TRX-Q971WS	0	0	27000
93	ORD-20260825135730	6	outlet 6	45000	0	45000	paid	2026-08-25 13:57:30	2026-08-25 13:57:30	6	cash	TRX-WAKVMX	0	0	45000
94	ORD-20260825142655	6	outlet 6	12000	0	12000	paid	2026-08-25 14:26:55	2026-08-25 14:26:55	6	cash	TRX-DFO9AT	0	0	12000
95	ORD-20260825144223	6	outlet 6	18000	0	18000	paid	2026-08-25 14:42:23	2026-08-25 14:42:23	6	cash	TRX-P8EM58	0	0	18000
96	ORD-20260825145345	6	outlet 6	12000	0	12000	paid	2026-08-25 14:53:45	2026-08-25 14:53:45	6	cash	TRX-P2AXBV	0	0	12000
97	ORD-20260825145923	6	outlet 6	12000	0	12000	paid	2026-08-25 14:59:23	2026-08-25 14:59:23	6	cash	TRX-KRKBDH	0	0	12000
98	ORD-20260825152134	6	outlet 6	12000	0	12000	paid	2026-08-25 15:21:34	2026-08-25 15:21:34	6	qris	TRX-DYZFDK	0	0	12000
99	ORD-20260825153220	6	outlet 6	12000	0	12000	paid	2026-08-25 15:32:20	2026-08-25 15:32:20	6	cash	TRX-516O9T	0	0	12000
100	ORD-20260825153722	6	outlet 6	21000	0	21000	paid	2026-08-25 15:37:22	2026-08-25 15:37:22	6	cash	TRX-FCPV4Q	0	0	21000
101	ORD-20260825160119	6	outlet 6	17000	0	17000	paid	2026-08-25 16:01:19	2026-08-25 16:01:19	6	cash	TRX-LY2RLE	0	0	17000
102	ORD-20260825160541	6	outlet 6	14000	0	14000	paid	2026-08-25 16:05:41	2026-08-25 16:05:41	6	cash	TRX-BFNWQV	0	0	14000
103	ORD-20260825161951	6	outlet 6	14000	0	14000	paid	2026-08-25 16:19:51	2026-08-25 16:19:51	6	cash	TRX-ILCU9E	0	0	14000
104	ORD-20260825162601	6	outlet 6	12000	0	12000	paid	2026-08-25 16:26:01	2026-08-25 16:26:01	6	cash	TRX-LKOCGA	0	0	12000
105	ORD-20260825162932	6	outlet 6	14000	0	14000	paid	2026-08-25 16:29:32	2026-08-25 16:29:32	6	cash	TRX-R9KFLQ	0	0	14000
106	ORD-20260825170314	6	outlet 6	18000	0	18000	paid	2026-08-25 17:03:14	2026-08-25 17:03:14	6	cash	TRX-WKWNM4	0	0	18000
107	ORD-20260825171014	6	outlet 6	9000	0	9000	paid	2026-08-25 17:10:14	2026-08-25 17:10:14	6	cash	TRX-9MTMUV	0	0	9000
108	ORD-20260825171411	6	outlet 6	18000	0	18000	paid	2026-08-25 17:14:11	2026-08-25 17:14:11	6	cash	TRX-YIBXOA	0	0	18000
109	ORD-20260825171931	6	outlet 6	18000	0	18000	paid	2026-08-25 17:19:31	2026-08-25 17:19:31	6	cash	TRX-4DMD4G	0	0	18000
110	ORD-20260825172135	6	outlet 6	9000	0	9000	paid	2026-08-25 17:21:35	2026-08-25 17:21:35	6	cash	TRX-SJQWPP	0	0	9000
111	ORD-20260825173408	6	outlet 6	9000	0	9000	paid	2026-08-25 17:34:08	2026-08-25 17:34:08	6	cash	TRX-05X8QW	0	0	9000
112	ORD-20260825175823	6	outlet 6	21000	0	21000	paid	2026-08-25 17:58:23	2026-08-25 17:58:23	6	qris	TRX-KRRETF	0	0	21000
113	ORD-20260825180439	6	outlet 6	45000	0	45000	paid	2026-08-25 18:04:39	2026-08-25 18:04:39	6	cash	TRX-IAALNK	0	0	45000
114	ORD-20260825182604	6	outlet 6	54000	0	54000	paid	2026-08-25 18:26:04	2026-08-25 18:26:04	6	cash	TRX-ERTKZG	0	0	54000
115	ORD-20260825184833	6	outlet 6	27000	0	27000	paid	2026-08-25 18:48:33	2026-08-25 18:48:33	6	cash	TRX-R7HSIW	0	0	27000
116	ORD-20260826093858	6	outlet 6	30000	0	30000	paid	2026-08-26 09:38:58	2026-08-26 09:38:58	6	cash	TRX-VTJGLG	0	0	30000
117	ORD-20260826111307	6	outlet 6	15000	0	15000	paid	2026-08-26 11:13:07	2026-08-26 11:13:07	6	qris	TRX-GB7Z6P	0	0	15000
118	ORD-20260826113206	6	outlet 6	9000	0	9000	paid	2026-08-26 11:32:06	2026-08-26 11:32:06	6	cash	TRX-GJ1HFY	0	0	9000
119	ORD-20260826113704	6	outlet 6	9000	0	9000	paid	2026-08-26 11:37:04	2026-08-26 11:37:04	6	cash	TRX-Y0RSBK	0	0	9000
120	ORD-20260826120101	6	outlet 6	18000	0	18000	paid	2026-08-26 12:01:01	2026-08-26 12:01:01	6	cash	TRX-QAJICA	0	0	18000
121	ORD-20260826124850	6	outlet 6	28000	0	28000	paid	2026-08-26 12:48:50	2026-08-26 12:48:50	6	cash	TRX-CBSX0P	0	0	28000
122	ORD-20260826125058	6	outlet 6	45000	0	45000	paid	2026-08-26 12:50:58	2026-08-26 12:50:58	6	cash	TRX-WIIOJQ	0	0	45000
123	ORD-20260826130905	6	outlet 6	9000	0	9000	paid	2026-08-26 13:09:05	2026-08-26 13:09:05	6	cash	TRX-6OHCQB	0	0	9000
124	ORD-20260826134722	6	outlet 6	9000	0	9000	paid	2026-08-26 13:47:22	2026-08-26 13:47:22	6	cash	TRX-JGFFTE	0	0	9000
125	ORD-20260826140722	6	outlet 6	9000	0	9000	paid	2026-08-26 14:07:22	2026-08-26 14:07:22	6	cash	TRX-H2NA5S	0	0	9000
126	ORD-20260826142923	6	outlet 6	21000	0	21000	paid	2026-08-26 14:29:23	2026-08-26 14:29:23	6	cash	TRX-DXDY8S	0	0	21000
127	ORD-20260826143635	6	outlet 6	36000	0	36000	paid	2026-08-26 14:36:35	2026-08-26 14:36:35	6	qris	TRX-DA6IXV	0	0	36000
128	ORD-20260826145705	6	outlet 6	9000	0	9000	paid	2026-08-26 14:57:05	2026-08-26 14:57:05	6	qris	TRX-WQDSP7	0	0	9000
129	ORD-20260826150744	6	outlet 6	29000	0	29000	paid	2026-08-26 15:07:44	2026-08-26 15:07:44	6	cash	TRX-WBIGDA	0	0	29000
130	ORD-20260826152803	6	outlet 6	12000	0	12000	paid	2026-08-26 15:28:03	2026-08-26 15:28:03	6	cash	TRX-ZUBSOM	0	0	12000
131	ORD-20260826153023	6	outlet 6	9000	0	9000	paid	2026-08-26 15:30:23	2026-08-26 15:30:23	6	qris	TRX-YPGGPP	0	0	9000
132	ORD-20260826162146	6	outlet 6	21000	0	21000	paid	2026-08-26 16:21:46	2026-08-26 16:21:46	6	cash	TRX-KUJGBN	0	0	21000
133	ORD-20260826175652	6	outlet 6	17000	0	17000	paid	2026-08-26 17:56:52	2026-08-26 17:56:52	6	cash	TRX-KHFLHT	0	0	17000
134	ORD-20260826184330	6	outlet 6	27000	0	27000	paid	2026-08-26 18:43:30	2026-08-26 18:43:30	6	cash	TRX-VEZXQD	0	0	27000
135	ORD-20260826184511	6	outlet 6	12000	0	12000	paid	2026-08-26 18:45:11	2026-08-26 18:45:11	6	cash	TRX-BB9JXY	0	0	12000
136	ORD-20260826184531	6	outlet 6	27000	0	27000	paid	2026-08-26 18:45:31	2026-08-26 18:45:31	6	cash	TRX-NQVKVJ	0	0	27000
137	ORD-20260826184547	6	outlet 6	27000	0	27000	paid	2026-08-26 18:45:47	2026-08-26 18:45:47	6	cash	TRX-XJ7XKH	0	0	27000
138	ORD-20260831010055	2	outlet 2	27000	0	27000	paid	2026-08-31 01:00:55	2026-08-31 01:00:55	2	cash	TRX-BUFUTJ	0	0	27000
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at, shift_started_at) FROM stdin;
10	SPV	spv@gmail.com	\N	$2y$12$f0ReHFLXIbCB94w51xhXYuIEq92RXK9ri1oW1F32RSVZBy30so4cC	SPV	\N	2026-08-22 17:54:23	2026-08-22 19:03:16	\N
1	Pusat	icippono@gmail.com	\N	$2y$12$NEWMlxPe1q04Y3FBUPolUO7LibrH6D9MT5eIZEEce9UsTI4jbX.Vm	outlet 1	\N	2026-08-22 17:45:48	2026-08-22 19:14:06	2026-08-22 19:14:06
2	Indomaret	wish_idm@gmail.com	\N	$2y$12$dZbEX1aapG2HUR6yVl4hF.ZoPam4Uar5eGicte1CkjtxrVET1hkx6	outlet 2	\N	2026-08-22 17:45:48	2026-08-22 19:27:14	2026-08-22 19:27:14
3	Bunderan	wish_bund@gmail.com	\N	$2y$12$zPCR7WlqOvtABqT2MvnPm.aA/OxsuHIpK2xtHNibe3G8iqgowFyri	outlet 3	\N	2026-08-22 17:45:49	2026-08-23 11:09:50	2026-08-23 11:09:50
4	Mersi	wish_mersi@gmail.com	\N	$2y$12$mffB7DfB4zv5VFB9MdnRpexKk25snKqanyGUVR/23W.hgxqdXkXv.	outlet 4	\N	2026-08-22 17:45:49	2026-08-23 11:36:31	2026-08-23 11:36:31
8	Admin	admin@gmail.com	\N	$2y$12$MNzO/.uj98RisJpV6fYjDe8SAhc.F8ZDbvtyE.G3BCmZK.EyNlbZ.	admin	\N	2026-08-22 17:53:07	2026-08-23 11:42:32	2026-08-23 11:42:32
6	Larangan	wish_lar@gmail.com	\N	$2y$12$9JiH16.WavoB9wJHeQy1jODLj5abNkWESwkXamIrhWSlZL/DbN4fm	outlet 6	\N	2026-08-22 17:45:49	2026-08-23 11:58:59	2026-08-23 11:58:59
5	Arca	wish_arca@gmail.com	\N	$2y$12$CW6zHdbofhnxgM/Or7UmWuUn9v2zTESSrurS.sN9m3fPQpIa3qVPO	outlet 5	\N	2026-08-22 17:45:49	2026-08-23 12:37:42	2026-08-23 12:37:42
7	Unsoed	wish_unsoed@gmail.com	\N	$2y$12$BlFCOc.iVTspIhFYyDZk3OVt4SdisOLQv3r7sbH0U.SdtS1xDvA.i	outlet 7	\N	2026-08-22 17:45:50	2026-08-23 12:58:35	2026-08-23 12:58:35
14	SPV 2	spv2@gmail.com	\N	$2y$12$5WZt2gIi5Jwwn1IHNAV7aeDY7eikxAXUTSWP8gCAaI8DUPkvfM4rK	SPV	\N	2026-09-02 16:05:19	2026-09-02 16:05:19	\N
15	SPV 3	spv3@gmail.com	\N	$2y$12$K1NVeXPTfbHGdf6Dq0RAiuFTsTcB4N2WDHH84ubj3yk77m59RsiwG	SPV	\N	2026-09-02 16:05:31	2026-09-02 16:05:31	\N
18	Event	eventwish@gmail.com	\N	$2y$12$mMmb4PufioJmTYskV1FmaukVVoa6dcl0F0qu.n5Ngkc5amwg2KAQW	Event	\N	2026-09-04 15:02:26	2026-09-04 15:02:26	\N
\.


--
-- Name: bahans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bahans_id_seq', 11, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: marinasi_batch_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marinasi_batch_items_id_seq', 1, false);


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

SELECT pg_catalog.setval('public.marinasi_items_id_seq', 31, true);


--
-- Name: marinasis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.marinasis_id_seq', 1, false);


--
-- Name: menus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.menus_id_seq', 9, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 14, true);


--
-- Name: shift_closings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.shift_closings_id_seq', 15, true);


--
-- Name: transaction_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transaction_items_id_seq', 175, true);


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transactions_id_seq', 138, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 18, true);


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
-- Name: marinasi_batch_items marinasi_batch_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_batch_items
    ADD CONSTRAINT marinasi_batch_items_pkey PRIMARY KEY (id);


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
-- Name: menus menus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menus
    ADD CONSTRAINT menus_pkey PRIMARY KEY (id);


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
-- Name: shift_closings shift_closings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.shift_closings
    ADD CONSTRAINT shift_closings_pkey PRIMARY KEY (id);


--
-- Name: transaction_items transaction_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction_items
    ADD CONSTRAINT transaction_items_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_order_number_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_order_number_unique UNIQUE (order_number);


--
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


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
-- Name: marinasi_items_bahan_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX marinasi_items_bahan_idx ON public.marinasi_items USING btree (bahan);


--
-- Name: marinasi_items_jenis_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX marinasi_items_jenis_idx ON public.marinasi_items USING btree (jenis);


--
-- Name: marinasi_items_marinasi_id_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX marinasi_items_marinasi_id_idx ON public.marinasi_items USING btree (marinasi_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: shift_closings_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX shift_closings_user_id_index ON public.shift_closings USING btree (user_id);


--
-- Name: marinasi_batch_items marinasi_batch_items_marinasi_batch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.marinasi_batch_items
    ADD CONSTRAINT marinasi_batch_items_marinasi_batch_id_foreign FOREIGN KEY (marinasi_batch_id) REFERENCES public.marinasi_batches(id) ON DELETE CASCADE;


--
-- Name: transaction_items transaction_items_transaction_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction_items
    ADD CONSTRAINT transaction_items_transaction_id_foreign FOREIGN KEY (transaction_id) REFERENCES public.transactions(id) ON DELETE CASCADE;


--
-- Name: transactions transactions_kasir_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_kasir_id_foreign FOREIGN KEY (kasir_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: transactions transactions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict U95NahgKEFtUPJRC1qwyHvPyYXTlYWBIZMkcyGIt13y7ErJ1KRi90OjmMh95X5a

