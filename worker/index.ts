import { Container, getContainer } from "@cloudflare/containers";

interface Env {
  LBAW: DurableObjectNamespace<LbawContainer>;
  APP_KEY: string;
  APP_URL: string;
  DB_URL: string;
  PUSHER_APP_ID?: string;
  PUSHER_APP_KEY?: string;
  PUSHER_APP_SECRET?: string;
  PUSHER_APP_CLUSTER?: string;
  GOOGLE_CLIENT_ID?: string;
  GOOGLE_CLIENT_SECRET?: string;
  GOOGLE_CALL_BACK_ROUTE?: string;
  FACEBOOK_CLIENT_ID?: string;
  FACEBOOK_CLIENT_SECRET?: string;
  FACEBOOK_CALL_BACK_ROUTE?: string;
}

export class LbawContainer extends Container<Env> {
  defaultPort = 80;
  sleepAfter = "2m";

  constructor(ctx: DurableObjectState, env: Env) {
    super(ctx, env);
    const vars: Record<string, string | undefined> = {
      APP_ENV: "production",
      APP_DEBUG: "false",
      APP_KEY: env.APP_KEY,
      APP_URL: env.APP_URL,
      DB_CONNECTION: "pgsql",
      DB_URL: env.DB_URL,
      DB_SCHEMA: "lbaw25122",
      SESSION_DRIVER: "cookie",
      CACHE_STORE: "array",
      QUEUE_CONNECTION: "sync",
      LOG_CHANNEL: "stderr",
      BROADCAST_CONNECTION: "pusher",
      PUSHER_APP_ID: env.PUSHER_APP_ID,
      PUSHER_APP_KEY: env.PUSHER_APP_KEY,
      PUSHER_APP_SECRET: env.PUSHER_APP_SECRET,
      PUSHER_APP_CLUSTER: env.PUSHER_APP_CLUSTER,
      GOOGLE_CLIENT_ID: env.GOOGLE_CLIENT_ID,
      GOOGLE_CLIENT_SECRET: env.GOOGLE_CLIENT_SECRET,
      GOOGLE_CALL_BACK_ROUTE: env.GOOGLE_CALL_BACK_ROUTE,
      FACEBOOK_CLIENT_ID: env.FACEBOOK_CLIENT_ID,
      FACEBOOK_CLIENT_SECRET: env.FACEBOOK_CLIENT_SECRET,
      FACEBOOK_CALL_BACK_ROUTE: env.FACEBOOK_CALL_BACK_ROUTE,
    };
    this.envVars = Object.fromEntries(
      Object.entries(vars).filter(([, v]) => v !== undefined)
    ) as Record<string, string>;
  }
}

export default {
  async fetch(request: Request, env: Env): Promise<Response> {
    return getContainer(env.LBAW).fetch(request);
  },
};
