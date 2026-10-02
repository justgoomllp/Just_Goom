# Agent Commission Module

**Author:** KP PATEL  
**ગુજરાતી:** [AGENT_MODULE_GU.md](AGENT_MODULE_GU.md)

This document explains how the agent module works: admin rates, customer referral, plan payment, profile-based extra commission, and the agent portal.

---

## Who does what

| Role | Where | What they do |
|------|--------|----------------|
| Admin | `/admin/users` | Creates the agent (email, password, referral code). |
| Admin | `/admin/commission` | Sets plan-wise **India %** and **Global %** for that agent. |
| Customer | `/register` | Enters the agent’s referral code. |
| Customer | `/users/subscription` | Pays for Silver / Gold / Platinum (full price). |
| Agent | `/login` then `/agent` | Sees customers, profile %, and earnings. |

Agents **do not** use `/admin/login`. They use the **customer login** page with the email and password set in Admin → Users.

---

## End-to-end flow

```text
Admin creates Agent
        |
        | referral code e.g. DEMI0001
        v
Admin sets rates (example: Silver India 10%)
        |
        v
Customer registers with DEMI0001
        |  referred_by_id = that agent
        v
Customer pays for a plan (e.g. Silver ₹3000)
        |
        | 50% of the 10% rule = 5% credited now
        v
Agent earns ₹150
        |
        | customer fills profile
        v
Profile 50%  -->  +2%  -->  ₹60 more
Profile 70%  -->  +3%  -->  ₹90 more
Profile 100% -->  remaining 5% already complete
        |
        v
Agent total for this customer = ₹300 (10% complete)
```

---

## Step 1 — Create the agent (Admin)

1. Open **Admin → Users → Add User**.
2. Type = **Agent**.
3. Set email, password (min 6 characters), phone, location.
4. Leave referral code empty to auto-generate, or enter one (example: `DEMI0001`).
5. Keep status **Active** and email **verified**.

The agent signs in at:

- URL: `/login` (not `/admin/login`)
- Email: the agent’s email (example: `demin_agent@gmail.com`)
- Password: the password you set on that user

---

## Step 2 — Set commission rates (Admin)

1. Open **Admin → Commission**.
2. Find the agent → **Set rates**.
3. Enter percent per plan:

| Plan | India % | Global % |
|------|---------|----------|
| Silver | 10 | 10 |
| Gold | 10 | 10 |
| Platinum | 10 | 10 |

**India vs Global:** taken from the **customer’s country**. India (or empty) uses India %. Any other country uses Global %. Payment is still INR.

If rates are **0** or not saved, the agent earns **nothing**.

---

## Step 3 — Customer registers with referral code

On `/register`, the customer optionally enters the agent code (`DEMI0001`).

- Code must match an **active agent**.
- The new customer is linked as `referred_by_id` = that agent.
- The customer still gets **their own** unique referral code (the agent’s code is not copied).

Without a valid agent code, no commission is created.

---

## Step 4 — Customer pays for a plan

Customer logs in, buys Silver / Gold / Platinum via Razorpay (full price, no discount).

On successful payment the system credits **50% of the admin rate** immediately.

### Example (source of truth)

Admin rate: **Silver / India / 10%**  
Customer pays: **₹3000**  
Full cap: **10% of ₹3000 = ₹300**

| When | What is credited | % of plan | Amount | Running total |
|------|------------------|-----------|--------|----------------|
| Plan payment (50% of the 10% rule) | Base earning | **5%** | **₹150** | ₹150 |
| Profile reaches **50%** | Extra slice | **+2%** | **₹60** | ₹210 |
| Profile reaches **70%** | Extra slice | **+3%** | **₹90** | ₹300 |
| Profile **100%** | Remaining 5% already done (2% + 3%) | — | ₹0 | **₹300 (10% complete)** |

The extra 2% / 3% is **not** a second customer payment. It unlocks as the **same customer** fills their profile after paying.

If the profile is already 70%+ when they pay, the base 5% and the matching profile slices are credited in the same payment.

---

## Step 5 — Profile % (how 50% / 70% is measured)

Profile % counts **8 About sections** (any row exists):

Team · Services · Products · Projects · Documents · Videos · Articles · Offers

Rough mapping:

- 0–2 sections → 0–40% (no extra commission yet)
- 4 sections → about **56%** → **+2%** unlocks
- 5+ sections → about **70%+** → **+2% and +3%** unlock

---

## Step 6 — Agent portal

After `/login` as agent, the agent is sent to `/agent`.

| Page | What they see |
|------|----------------|
| Dashboard | Customer count, total earned, this month, recent credits |
| Customers | Name, email, plan, **profile %**, earned so far |
| Customer profile | Company details, profile %, payment + 5% / +2% / +3% breakdown |
| Earnings | Every credit row: date, customer, plan, paid amount, profile %, rate, commission |

---

## Formula (any plan / any %)

Let **R** = admin rate for that agent + plan + region (example R = 10).  
Let **A** = amount paid (example A = 3000).

- On payment: credit `(R / 2)%` of A → 5% × 3000 = **₹150**
- Profile ≥ 50%: credit `40%` of that remaining half → **+2%** when R = 10 → **₹60**
- Profile ≥ 70%: credit `60%` of that remaining half → **+3%** when R = 10 → **₹90**
- Cap per payment = **R% of A**. Never more. Same slice is never credited twice.

Renewal or upgrade starts a **new cycle** on the new paid amount.

---

## Test data seeder (10 customers on DEMI0001)

Requires an active agent whose referral code is **DEMI0001**.

```bash
php artisan db:seed --class=AgentReferralCommissionSeeder
```

What it does:

- Sets that agent’s Silver / Gold / Platinum rates to **10%** India and Global (if missing).
- Creates **10 customers** referred by `DEMI0001`.
- Each customer buys **Silver ₹3000** (seeded as paid).
- Mixes profile fill so you can see all three commission slices.

| Customers | Profile | Expected commission each |
|-----------|---------|---------------------------|
| 1–3 | 0% | ₹150 (5% only) |
| 4–6 | ~56% | ₹210 (5% + 2%) |
| 7–10 | 70%+ | ₹300 (5% + 2% + 3%) |

Customer login (for inspecting profiles):

- Email: `demi.ref.01@justgoom.test` … `demi.ref.10@justgoom.test`
- Password: `password123`

Then log in as the **DEMI0001** agent and open **Customers** and **Earnings**.

---

## What will not earn commission

- Customer registered **without** a valid agent referral code.
- Agent is inactive.
- Admin never saved rates, or rate is 0.
- Payment failed / not paid.
- Agent trying to log in at `/admin/login` (use `/login` instead).

---

## Quick check after a live referral

1. Admin → Commission → Silver India **10%** saved for that agent.  
2. Customer registered with the agent code.  
3. Customer paid Silver ₹3000.  
4. Agent `/agent/earnings` shows **Payment (50% of rate)** ₹150.  
5. Customer adds About sections until profile ≥ 50%, then ≥ 70%.  
6. Earnings shows **Profile 50%** ₹60 and **Profile 70%** ₹90.  
7. Total for that customer = **₹300**.
