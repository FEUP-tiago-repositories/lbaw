import { Container, getContainer } from "@cloudflare/containers";

const PASSTHROUGH = [
  "APP_KEY", "APP_URL", "DB_URL", "DB_SCHEMA",
  "PUSHER_APP_ID", "PUSHER_APP_KEY", "PUSHER_APP_SECRET", "PUSHER_APP_CLUSTER",
  "GOOGLE_CLIENT_ID", "GOOGLE_CLIENT_SECRET", "GOOGLE_CALL_BACK_ROUTE",
  "FACEBOOK_CLIENT_ID", "FACEBOOK_CLIENT_SECRET", "FACEBOOK_CALL_BACK_ROUTE",
];

export class LbawContainer extends Container {
  defaultPort = 80;
  sleepAfter = "2m";

  constructor(ctx: DurableObjectState, env: any) {
    super(ctx, env);
    const vars: Record<string, string> = {
      APP_ENV: "production",
      APP_DEBUG: "false",
      DB_CONNECTION: "pgsql",
      SESSION_DRIVER: "cookie",
      CACHE_STORE: "array",
      QUEUE_CONNECTION: "sync",
      LOG_CHANNEL: "stderr",
      BROADCAST_CONNECTION: "pusher",
    };
    for (const name of PASSTHROUGH) {
      if (typeof env[name] === "string") vars[name] = env[name];
    }
    this.envVars = vars;
  }
}

export default {
  async fetch(request: Request, env: any): Promise<Response> {
    return getContainer(env.LBAW).fetch(request);
  },
};
